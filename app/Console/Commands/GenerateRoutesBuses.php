<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class GenerateRoutesBuses extends Command
{
    protected $signature = 'generate:routes-buses';
    protected $description = 'Generate routes buses for SEO';
   

    public function handle()
    {
        try {
            DB::beginTransaction();
            $startTime = microtime(true);
            Log::info('Generate routes for SEO started at: ' . now());

            $routeBusData = DB::table('ticket_price as tp')
                                ->join('bus as b', 'tp.bus_id', '=', 'b.id')
                                ->join('mst_routes_details as r', function ($join) {
                                    $join->on('tp.source_id', '=', 'r.source_id')
                                        ->on('tp.destination_id', '=', 'r.destination_id');
                                })
                                ->select(
                                        'r.id as route_id',
                                        'tp.bus_id',
                                        'tp.source_id',
                                        'tp.destination_id',
                                        'tp.dep_time',
                                        'tp.arr_time',
                                        DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, tp.dep_time, tp.arr_time) / 60, 1) as duration_hours')
                                )
                                ->where('r.is_bus_added', 0)
                                ->where('tp.status', 1)
                                ->where('b.status', 1)
                                ->limit(1000)
                                ->distinct()
                                ->get();

            // log::info($routeBusData); exit;

            $busIds = collect($routeBusData)->pluck('bus_id')->unique()->toArray();
            $busFareMap = $this->getBusFareMap($busIds);

            $insertBusMap = [];
            $route_ids = [];
            $locationIds = [];

            if(!empty($routeBusData)){

                foreach ($routeBusData as $item) {

                    $fare = $busFareMap[$item->bus_id] ?? ['min_fare' => 0, 'max_fare' => 0];

                    $insertBusMap[] = [
                        'route_id' => $item->route_id,
                        'bus_id' => $item->bus_id,
                        'dep_time'=>$item->dep_time,
                        'arr_time'=>$item->arr_time,
                        'duration_hours'=>$item->duration_hours,
                        'min_fare' => $fare['min_fare'],
                        'max_fare' => $fare['max_fare']
                    ];

                    $route_ids[] = $item->route_id;

                    $locationIds[] = $item->source_id;
                    $locationIds[] = $item->destination_id;
                }

                $locationIds = array_values(array_unique($locationIds));

                $locations = DB::table('location')
                                ->whereIn('id', $locationIds)
                                ->pluck('url', 'id');

                $uniqueSeoContent  = [];

                foreach ($routeBusData as $item) {

                        $sourceName = $locations[$item->source_id] ?? '';
                        $destinationName = $locations[$item->destination_id] ?? '';

                         if (!$sourceName || !$destinationName) {
                            continue;
                        }

                        $url = strtolower(str_replace(' ', '-', $sourceName))
                            . '-to-' .
                            strtolower(str_replace(' ', '-', $destinationName))
                            . '-bus-services';

                        $uniqueSeoContent[$item->route_id] = [
                            'route_id' => $item->route_id,
                            'seo_template_id' => 1,
                            'url' => $url
                        ];
                }

                $insSeoContent = array_values($uniqueSeoContent);

                $route_ids = array_values(array_unique($route_ids));

                $countids = count($route_ids);

                if (!empty($insertBusMap)) {
                    DB::table('mst_routes_bus_ids')->insertOrIgnore($insertBusMap);
                }

                if (!empty($insSeoContent)) {
                    DB::table('mst_seo_content')->insertOrIgnore($insSeoContent);
                }

                DB::table('mst_routes_details')
                    ->whereIn('id', $route_ids)
                    ->update(['is_bus_added' => 1]);

                Log::info('New routes buses for SEO: ' . $countids .' Nos' );

            }

            DB::commit();

           Artisan::call('generate:routes-operators');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
        } finally {
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            
            Log::info('Generate routes buses for SEO ended at: ' . now());
            Log::info("Execution time: {$executionTime} seconds");
            Log::info("Processed 10000 records in {$executionTime} sec");
        }
    }

    public function getBusFareMap($busIds)
    {
        $busPrices = DB::table('bus as b')
            ->join('ticket_price as tp', 'tp.bus_id', '=', 'b.id')
            ->leftJoin('bus_seats as bs', function ($join) {
                $join->on('bs.bus_id', '=', 'b.id')
                    ->on('bs.ticket_price_id', '=', 'tp.id')
                    ->where('bs.new_fare', '!=', 0.00);
            })
            ->whereIn('b.id', $busIds)
            ->select(
                'b.id as bus_id',

                DB::raw('MIN(
                    CASE
                        WHEN bs.new_fare IS NOT NULL AND bs.new_fare != 0.00
                        THEN bs.new_fare
                        ELSE tp.base_seat_fare
                    END
                ) as min_fare'),

                DB::raw('MAX(
                    CASE
                        WHEN bs.new_fare IS NOT NULL AND bs.new_fare != 0.00
                        THEN bs.new_fare
                        ELSE tp.base_seat_fare
                    END
                ) as max_fare')
            )
            ->groupBy('b.id')
            ->get();

        // 🔥 Convert to mapping
        $map = [];

        foreach ($busPrices as $bus) {
            $map[$bus->bus_id] = [
                'min_fare' => $bus->min_fare,
                'max_fare' => $bus->max_fare
            ];
        }

        return $map;
    }
}
