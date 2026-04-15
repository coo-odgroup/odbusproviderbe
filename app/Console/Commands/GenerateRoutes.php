<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GenerateRoutes extends Command
{
    protected $signature = 'generate:routes';
    protected $description = 'Generate routes for SEO';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            DB::beginTransaction();
            $startTime = microtime(true);
            Log::info('Generate routes for SEO started at: ' . now());
 
            // 1. Fetch 100 unprocessed rows
            $rows = DB::table('ticket_price as tp')
                        ->select('id','source_id','destination_id','bus_id')
                        ->where('status', 1)
                        ->where('is_new_route', 0)
                         ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('bus as bs')
                                ->whereColumn('bs.id', 'tp.bus_id')
                                ->where('bs.status', 1);
                         })
                        ->limit(100)
                        ->get();


            if ($rows->isEmpty()) {
                $this->info('No new routes found');
                return;
            }

            // 3. Filter already existing routes (OPTIMIZED)
            $filteredRoutes = DB::table('ticket_price as rt')
                                    ->leftJoin('mst_routes_details as r', function ($join) {
                                        $join->on('rt.source_id', '=', 'r.source_id')
                                            ->on('rt.destination_id', '=', 'r.destination_id');
                                    })
                                    ->leftJoin('location as src', 'src.id', '=', 'rt.source_id')
                                    ->leftJoin('location as dest', 'dest.id', '=', 'rt.destination_id')
                                    ->where('rt.status', 1)
                                    ->where('rt.is_new_route', 0)
                                    ->whereNull('r.id')
                                    ->whereExists(function ($query) {
                                        $query->select(DB::raw(1))
                                            ->from('bus as b')
                                            ->whereColumn('b.id', 'rt.bus_id')
                                            ->where('b.status', 1);
                                     })
                                    ->select('rt.source_id', 'rt.destination_id','rt.bus_id','r.id',
                                             DB::raw('src.name as source_name'),
                                             DB::raw('dest.name as destination_name'))
                                    ->distinct()
                                    ->limit(100)
                                    ->get();

            

            $insertData = [];

            foreach ($filteredRoutes as $route) {
                $insertData[] = [
                    'source_id' => $route->source_id,
                    'destination_id' => $route->destination_id,
                    'source' => ucwords(strtolower($route->source_name)),
                    'destination' => ucwords(strtolower($route->destination_name)),
                    'active_status' => 1
                ];
            }

            // log::info($insertData); exit;

            if (!empty($insertData)) {
                DB::table('mst_routes_details')->insertOrIgnore($insertData);
            }

            // 5. Update ONLY those 100 rows processed
            $ids = $rows->pluck('id')->toArray();

            $countids = count($ids);

            DB::table('ticket_price')
                ->whereIn('id', $ids)
                ->update(['is_new_route' => 1]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
        } finally {
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            Log::info('New routes for SEO: ' . $countids .' Nos' );
            Log::info('Generate routes for SEO ended at: ' . now());
            Log::info("Execution time: {$executionTime} seconds");
            $this->info("Processed 10000 records in {$executionTime} sec");
        }
    }
}
