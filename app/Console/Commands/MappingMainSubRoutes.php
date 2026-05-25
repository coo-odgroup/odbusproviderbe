<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class MappingMainSubRoutes extends Command
{
    protected $signature = 'mapping:main-sub-routes';
    protected $description = 'Mapping Main & Sub for SEO';


    public function handle()
    {
        try {

            $startTime = microtime(true);
            Log::info('Mapping Main & Sub for SEO started at: ' . now());

            DB::beginTransaction();

            // 🔹 Get bus_ids
            $busIds = DB::table('ticket_price as tp')
                        ->join('bus as b', 'b.id', '=', 'tp.bus_id')
                        // ->where('tp.status', 1)
                        ->where('b.status', 1)
                        ->where('tp.is_route_mapped', 0)
                        ->distinct()
                        ->limit(1000)
                        ->pluck('tp.bus_id')
                        ->toArray();

            // log::info($busIds); exit;

            if (empty($busIds)) {
                return;
            }

            $mainRouteIdsMap = [];
            $parentMap = [];

            // Get main routes
           $mainRoutes = DB::table(DB::raw("(
                            SELECT
                                t.bus_id,
                                (SELECT location_id FROM bus_location_sequence
                                    WHERE bus_id = t.bus_id
                                    ORDER BY sequence ASC LIMIT 1) as source_id,
                                (SELECT location_id FROM bus_location_sequence
                                    WHERE bus_id = t.bus_id
                                    ORDER BY sequence DESC LIMIT 1) as destination_id
                            FROM bus_location_sequence t
                            WHERE t.bus_id IN (" . implode(',', $busIds) . ")
                            GROUP BY t.bus_id
                        ) as x"))
                        ->select('bus_id', 'source_id', 'destination_id') // KEEP bus_id
                        ->get();

            if ($mainRoutes->isEmpty()) {
                return;
            }

            $idMap = [];   // route_id => parent_route_id
            $mainIds = []; // main route ids

            foreach ($mainRoutes as $d) {

                $mainRouteId = DB::table('mst_routes_details')
                    ->where('source_id', $d->source_id)
                    ->where('destination_id', $d->destination_id)
                    ->value('id');

                if (!$mainRouteId) continue;

                // Store main route
                $mainIds[$mainRouteId] = true;

                // Get sub routes
                $subRoutes = DB::table('ticket_price')
                    ->where('bus_id', $d->bus_id)
                    ->select('source_id', 'destination_id')
                    ->distinct()
                    ->get();

                foreach ($subRoutes as $sub) {

                    // skip main route
                    // if ($sub->source_id == $d->source_id && $sub->destination_id == $d->destination_id) {
                    //     continue;
                    // }

                    $subRouteId = DB::table('mst_routes_details')
                                    ->where('source_id', $sub->source_id)
                                    ->where('destination_id', $sub->destination_id)
                                    ->value('id');

                    if (!$subRouteId) continue;

                      $idMap[] = [
                        'route_id' => $subRouteId,
                        'parent_route_id' => $mainRouteId
                    ];
                }
            }

            // log::info($mainIds);
            // log::info($idMap); exit;
          
            // Convert to array
            $mainRouteIds = array_keys($mainIds);


            // CASE UPDATE
            $ids = [];
            $isMainCase = '';

            foreach ($mainRouteIds as $id) {
                $ids[] = $id;
                $isMainCase .= "WHEN {$id} THEN 1 ";
            }
         
            if (!empty($ids)) {

                $ids = array_unique($ids);
                $idsString = implode(',', $ids);

                DB::statement("
                    UPDATE mst_routes_details
                    SET
                        is_main_route = CASE id {$isMainCase} END
                    WHERE id IN ({$idsString})
                ");
            }

            $insertData = [];

            // 🔹 Main routes
            foreach (array_keys($mainIds) as $mainId) {
                $insertData[] = [
                    'route_id' => $mainId,
                    'parent_route_id' => null,
                    'is_main_route' => 1,
                    'created_by' => 1
                ];
            }

           // Sub routes
            foreach ($idMap as $map) {
                $insertData[] = [
                    'route_id' => $map['route_id'],
                    'parent_route_id' => $map['parent_route_id'],
                    'is_main_route' => 0,
                    'created_by' => 1
                ];
            }

            // log::info($insertData);exit;

            DB::table('mst_route_map')->insertOrIgnore($insertData);

            //  Mark processed
            DB::table('ticket_price')
                ->whereIn('bus_id', $busIds)
                ->where('status', 1)
                ->update(['is_route_mapped' => 1]);

            DB::commit();

            $this->info("Main & Sub routes mapped successfully");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        } finally {
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            Log::info('Main & Sub routes for SEO ended at: ' . now());
            Log::info("Execution time: {$executionTime} seconds");
            Log::info("Processed Main & Sub routes records in {$executionTime} sec");
        }
    }
}
