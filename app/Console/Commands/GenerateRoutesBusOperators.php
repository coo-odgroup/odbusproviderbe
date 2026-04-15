<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GenerateRoutesBusOperators extends Command
{
    protected $signature = 'generate:routes-operators';
    protected $description = 'Generate routes operators for SEO';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {

            DB::beginTransaction();

            $startTime = microtime(true);
            Log::info('Generate routes operators for SEO started at: ' . now());

            $routes = DB::table('mst_routes_details')
                        ->where('is_operator_added', 0)
                        ->limit(100)
                        ->get();

            if ($routes->isEmpty()) {
                $this->info('No routes found');
                return;
            }

            $routeIds = $routes->pluck('id')->toArray();

            DB::insert("
                INSERT IGNORE INTO mst_routes_operators (route_id, operator_id,url_genrated)
                SELECT DISTINCT
                    rb.route_id,
                    b.bus_operator_id as operator_id,
                    CONCAT('https://www.odbus.in/operator/', bo.operator_url) as operator_url
                FROM mst_routes_bus_ids rb
                JOIN bus b ON b.id = rb.bus_id
                JOIN bus_operator bo ON bo.id = b.bus_operator_id
                WHERE rb.route_id IN (" . implode(',', $routeIds) . ")
            ");

            DB::insert("
                INSERT IGNORE INTO mst_routes_bus_types (route_id, bus_description)

                SELECT DISTINCT
                    rb.route_id,
                    b.bus_description as bus_description
                    
                FROM mst_routes_bus_ids rb
                JOIN bus b ON b.id = rb.bus_id
                WHERE rb.route_id IN (" . implode(',', $routeIds) . ")
            ");

            $busCounts = DB::table('mst_routes_bus_ids')
                            ->whereIn('route_id', $routeIds)
                            ->select('route_id', DB::raw('COUNT(bus_id) as bus_count'))
                            ->groupBy('route_id')
                            ->pluck('bus_count', 'route_id');

            $operatorCounts = DB::table('mst_routes_operators')
                                ->whereIn('route_id', $routeIds)
                                ->select('route_id', DB::raw('COUNT(operator_id) as operator_count'))
                                ->groupBy('route_id')
                                ->pluck('operator_count', 'route_id');

            $ids = [];
            $busCase = '';
            $operatorCase = '';
            $isOperatorAddedCase = '';
            $isBusTypeAddedCase = '';

            foreach ($routeIds as $routeId) {

                $busCount = $busCounts[$routeId] ?? 0;
                $operatorCount = $operatorCounts[$routeId] ?? 0;

                $ids[] = $routeId;

                $busCase .= "WHEN {$routeId} THEN {$busCount} ";
                $operatorCase .= "WHEN {$routeId} THEN {$operatorCount} ";
                $isOperatorAddedCase .= "WHEN {$routeId} THEN 1 ";
                $isBusTypeAddedCase .= "WHEN {$routeId} THEN 1 ";
            }

            $idsString = implode(',', $ids);

            DB::statement("
                    UPDATE mst_routes_details
                    SET
                        bus_count = CASE id {$busCase} END,
                        operators_count = CASE id {$operatorCase} END,
                        is_operator_added = CASE id {$isOperatorAddedCase} END,
                        is_bus_type_added = CASE id {$isBusTypeAddedCase} END
                    WHERE id IN ({$idsString})
                ");

            // $updateData = [];

            // foreach ($routeIds as $routeId) {

            //     $updateData[] = [
            //         'id' => $routeId,
            //         'bus_count' => $busCounts[$routeId] ?? 0,
            //         'operators_count' => $operatorCounts[$routeId] ?? 0,
            //         'is_operator_added' => 1,
            //         'is_bus_type_added' => 1
            //     ];
            // }

            // // log::info($updateData);exit;


            // $update = DB::table('mst_routes_details')->upsert(
            //         $updateData,
            //         ['id'], // unique key
            //         ['bus_count', 'operators_count', 'is_operator_added', 'is_bus_type_added']
            //     );

            // log::info($update);     exit;

            // // 5. Update routes as processed
            // DB::table('mst_routes_details')
            //     ->whereIn('id', $routeIds)
            //     ->update(['is_operator_added' => 1,
            //               'is_bus_type_added' => 1]);

            $countids = count($routeIds);


            Log::info('New routes buses for SEO: ' . $countids .' Nos' );

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
        } finally {
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            
            Log::info('Generate routes operators for SEO ended at: ' . now());
            Log::info("Execution time: {$executionTime} seconds");
            $this->info("Processed records in {$executionTime} sec");
        }
    }

    public function busCounts($routeIds){

        $busCounts = DB::table('mst_routes_bus_ids')
                            ->whereIn('route_id', $routeIds)
                            ->select('route_id', DB::raw('COUNT(bus_id) as bus_count'))
                            ->groupBy('route_id')
                            ->pluck('bus_count', 'route_id');

        return $busCounts;
    }

    public function operatorCounts($routeIds){

        $operatorCounts = DB::table('mst_routes_operators')
                                ->whereIn('route_id', $routeIds)
                                ->select('route_id', DB::raw('COUNT(operator_id) as operator_count'))
                                ->groupBy('route_id')
                                ->pluck('operator_count', 'route_id');

        return $operatorCounts;
    }
}
