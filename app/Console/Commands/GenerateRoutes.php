<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class GenerateRoutes extends Command
{
    protected $signature = 'generate:routes';
    protected $description = 'Generate routes for SEO';
  

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
                        // ->where('is_new_route', 0)
                         ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('bus as bs')
                                ->whereColumn('bs.id', 'tp.bus_id')
                                ->where('bs.status', 1);
                         })
                        // ->limit(100)
                        ->get();

            // log::info($rows);exit;            


            if ($rows->isEmpty()) {
                $this->info('No new routes found');
                return;
            }

            $busIds = $rows->pluck('bus_id')->unique()->toArray();

            // 3. Filter already existing routes (OPTIMIZED)
        //    DB::statement("
        //                 INSERT INTO mst_routes_details 
        //                     (source_id, destination_id, source, destination, active_status, created_at)

        //                 SELECT 
        //                     x.source_id,
        //                     x.destination_id,
        //                     src.name as source,
        //                     dest.name as destination,
        //                     1,
        //                     NOW()

        //                 FROM (
        //                     SELECT DISTINCT 
        //                         source_id, 
        //                         destination_id
        //                     FROM (
        //                         SELECT 
        //                             t.bus_id,
        //                             SUBSTRING_INDEX(
        //                                 GROUP_CONCAT(t.location_id ORDER BY t.sequence ASC), ',', 1
        //                             ) AS source_id,
        //                             SUBSTRING_INDEX(
        //                                 GROUP_CONCAT(t.location_id ORDER BY t.sequence DESC), ',', 1
        //                             ) AS destination_id
        //                         FROM bus_location_sequence t
        //                         INNER JOIN bus b ON b.id = t.bus_id
        //                         WHERE b.status = 1
        //                         AND t.bus_id IN (" . implode(',', $busIds) . ")
        //                         GROUP BY t.bus_id
        //                     ) temp
        //                 ) x

        //                 LEFT JOIN mst_routes_details r
        //                     ON r.source_id = x.source_id
        //                     AND r.destination_id = x.destination_id

        //                 LEFT JOIN location src ON src.id = x.source_id
        //                 LEFT JOIN location dest ON dest.id = x.destination_id

        //                 WHERE r.id IS NULL
        //             ");

        DB::statement("
                INSERT INTO mst_routes_details 
                    (source_id, destination_id, source, destination, active_status, created_at)

                SELECT DISTINCT
                    tp.source_id,
                    tp.destination_id,
                    src.name as source,
                    dest.name as destination,
                    1,
                    NOW()

                FROM ticket_price tp

                INNER JOIN bus b ON b.id = tp.bus_id

                -- MAIN ROUTE (first & last stop per bus)
                INNER JOIN (
                    SELECT 
                        t.bus_id,
                        SUBSTRING_INDEX(GROUP_CONCAT(t.location_id ORDER BY t.sequence ASC), ',', 1) AS main_source,
                        SUBSTRING_INDEX(GROUP_CONCAT(t.location_id ORDER BY t.sequence DESC), ',', 1) AS main_destination
                    FROM bus_location_sequence t
                    WHERE t.bus_id IN (" . implode(',', $busIds) . ")
                    GROUP BY t.bus_id
                ) main_route 
                    ON main_route.bus_id = tp.bus_id

                LEFT JOIN mst_routes_details r
                    ON r.source_id = tp.source_id
                    AND r.destination_id = tp.destination_id

                LEFT JOIN location src ON src.id = tp.source_id
                LEFT JOIN location dest ON dest.id = tp.destination_id

                WHERE b.status = 1
                AND tp.status = 1
                AND tp.bus_id IN (" . implode(',', $busIds) . ")

                
                AND NOT (
                    tp.source_id = main_route.main_source
                    AND tp.destination_id = main_route.main_destination
                )
                
                AND r.id IS NULL
            ");

            // 5. Update ONLY those 100 rows processed
            $ids = $rows->pluck('id')->toArray();

            $countids = count($ids);

            DB::table('ticket_price')
                ->whereIn('id', $ids)
                ->update(['is_new_route' => 1]);

            DB::commit();

            // Artisan::call('generate:routes-buses');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
        } finally {
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            Log::info('New routes for SEO: ' . $countids .' Nos' );
            Log::info('Generate routes for SEO ended at: ' . now());
            Log::info("Execution time: {$executionTime} seconds");
            $this->info("Processed 100 records in {$executionTime} sec");
        }
    }
}
