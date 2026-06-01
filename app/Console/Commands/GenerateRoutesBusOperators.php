<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GenerateRoutesBusOperators extends Command
{
    protected $signature = 'generate:routes-operators';
    protected $description = 'Generate routes operators for SEO';

    public function handle()
    {
        try {

            DB::beginTransaction();

            $startTime = microtime(true);
            Log::info('Generate routes operators for SEO started at: ' . now());

            $routes = DB::table('mst_routes_details')
                        ->select('id')
                        ->where('is_operator_added', 0)
                        ->where('is_bus_added', 1)
                        ->orderby('id','asc')
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

            $routeStats = DB::table('mst_routes_bus_ids')
                            ->whereIn('route_id', $routeIds)
                            ->select(
                                'route_id',

                                DB::raw('MIN(CAST(min_fare AS DECIMAL(10,2))) as min_fare'),
                                DB::raw('MAX(CAST(max_fare AS DECIMAL(10,2))) as max_fare'),

                                DB::raw('MIN(dep_time) as first_bus_timing'),
                                DB::raw('MAX(dep_time) as last_bus_timing'),

                                DB::raw('MIN(CAST(duration_hours AS DECIMAL(5,1))) as min_duration'),
                                DB::raw('MAX(CAST(duration_hours AS DECIMAL(5,1))) as max_duration')
                            )
                            ->groupBy('route_id')
                            ->get()
                            ->keyBy('route_id');

           $routesData = DB::table('mst_routes_bus_ids as rb')
                        ->join('bus as b', 'b.id', '=', 'rb.bus_id')
                        ->join('mst_routes_details as r', 'r.id', '=', 'rb.route_id')
                        ->whereIn('rb.route_id', $routeIds)
                        ->select(
                            'rb.route_id',
                            'r.source_id',
                            'r.destination_id',
                            DB::raw("GROUP_CONCAT(DISTINCT b.bus_description ORDER BY b.bus_description SEPARATOR ', ') as bus_types")
                        )
                        ->groupBy('rb.route_id', 'r.source_id', 'r.destination_id')
                        ->get();

           $sourceIds = collect($routesData)->pluck('source_id')->unique()->toArray();
           $destinationIds = collect($routesData)->pluck('destination_id')->unique()->toArray();

            $busIds = DB::table('mst_routes_bus_ids')
                            ->whereIn('route_id', $routeIds)
                            ->pluck('bus_id')
                            ->unique()
                            ->toArray();

            $busTypeHtmlMap  = [];
            $busTypeCommaMap = [];

            $routeFinalData = [];

           foreach ($routesData as $item) {

                $types = explode(',', $item->bus_types);

                $types = array_map(function ($type) {
                    $type = trim($type);
                    $type = str_replace('\/', '/', $type);
                    return htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
                }, $types);

                $types = array_filter($types);
                $types = array_unique($types);

                // 🔹 UL/LI
                $li = array_map(fn($t) => "<li>{$t}</li>", $types);
                $busTypeHtmlMap[$item->route_id] = '<ul class="seo-list">' . implode('', $li) . '</ul>';

                // 🔹 Comma separated
                $busTypeCommaMap[$item->route_id] = implode(', ', $types);


                $boardingPoints = DB::table('bus_stoppage_timing as bst')
                                    ->join('location as loc', 'loc.id', '=', 'bst.location_id')
                                    ->whereIn('bst.bus_id', $busIds)
                                    ->where('bst.location_id', $item->source_id)
                                    ->pluck('loc.name')
                                    ->unique()
                                    ->toArray();

                $droppingPoints = DB::table('bus_stoppage_timing as bst')
                                    ->join('location as loc', 'loc.id', '=', 'bst.location_id')
                                    ->whereIn('bst.bus_id', $busIds)
                                    ->where('bst.location_id', $item->destination_id)
                                    ->pluck('loc.name')
                                    ->unique()
                                    ->toArray();


                $routeFinalData[$item->route_id] = [
                    'source_id' => $item->source_id,
                    'destination_id' => $item->destination_id,
                    'boarding_points' => implode(', ', $boardingPoints),
                    'dropping_points' => implode(', ', $droppingPoints),
                ];
            }

            $ids = [];
            $busCase = '';
            $operatorCase = '';
            $isOperatorAddedCase = '';
            $isBusTypeAddedCase = '';
            $minFareCase = '';
            $maxFareCase = '';
            $firstBusCase = '';
            $lastBusCase = '';
            $durationCase = '';
            $busTypeCommaSeparatedCases = '';
            $busTypeCases = '';
            $boardingCase = '';
            $droppingCase = '';

            foreach ($routeIds as $routeId) {

                $busCount = $busCounts[$routeId] ?? 0;
                $operatorCount = $operatorCounts[$routeId] ?? 0;

                $stats = $routeStats[$routeId] ?? null;

                $minFare = $stats->min_fare ?? 0;
                $maxFare = $stats->max_fare ?? 0;
                $firstBus = $stats->first_bus_timing ?? null;
                $lastBus = $stats->last_bus_timing ?? null;
                $busTypeHtml = $busTypeHtmlMap[$routeId] ?? '';
                $busTypeComma = $busTypeCommaMap[$routeId] ?? '';

                  // UPDATED DURATION LOGIC
                if ($stats && $stats->min_duration !== null && $stats->max_duration !== null) {

                    $min = floor($stats->min_duration);
                    $max = ceil($stats->max_duration);

                    $min = max(0, $min);
                    $max = max(0, $max);

                    if ($min == $max) {
                        $max = $min + 1;
                    }

                    $duration = $min . '-' . $max;

                } else {
                    $duration = '0-0';
                }

                $ids[] = $routeId;

                $busCase .= "WHEN {$routeId} THEN {$busCount} ";
                $operatorCase .= "WHEN {$routeId} THEN {$operatorCount} ";
                $isOperatorAddedCase .= "WHEN {$routeId} THEN 1 ";
                $isBusTypeAddedCase .= "WHEN {$routeId} THEN 1 ";

                $minFareCase .= "WHEN {$routeId} THEN {$minFare} ";
                $maxFareCase .= "WHEN {$routeId} THEN {$maxFare} ";

                $firstBusFormatted = $firstBus ? date('H:i', strtotime($firstBus)) : null;
                $lastBusFormatted = $lastBus ? date('H:i', strtotime($lastBus)) : null;

                $firstBusCase .= $firstBusFormatted
                    ? "WHEN {$routeId} THEN '{$firstBusFormatted}' "
                    : "WHEN {$routeId} THEN NULL ";

                $lastBusCase .= $lastBusFormatted
                    ? "WHEN {$routeId} THEN '{$lastBusFormatted}' "
                    : "WHEN {$routeId} THEN NULL ";

                $durationCase .= "WHEN {$routeId} THEN '{$duration}' ";

                $busTypeCases .= "WHEN {$routeId} THEN  '{$busTypeHtml}'";
                $busTypeCommaSeparatedCases .= "WHEN {$routeId} THEN '{$busTypeComma}' ";

                // $boarding = $routeFinalData[$routeId]['boarding_points'] ?? '';
                // $dropping = $routeFinalData[$routeId]['dropping_points'] ?? '';

                // 🔹 Convert to array
                // $boardingArr = array_filter(array_map('trim', explode(',', $boarding)));
                // $droppingArr = array_filter(array_map('trim', explode(',', $dropping)));

                // // 🔹 Encode + clean
                // $boardingArr = array_map(function ($item) {
                //     return htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
                // }, $boardingArr);

                // $droppingArr = array_map(function ($item) {
                //     return htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
                // }, $droppingArr);

                // 🔹 Convert to <li>
                // $boardingLi = array_map(fn($t) => "<li>{$t}</li>", $boardingArr);
                // $droppingLi = array_map(fn($t) => "<li>{$t}</li>", $droppingArr);

                // 🔹 Final UL
                // $boardingHtml = '<ul class="seo-list">' . implode('', $boardingLi) . '</ul>';
                // $droppingHtml = '<ul class="seo-list">' . implode('', $droppingLi) . '</ul>';

                // $boardingCase .= "WHEN {$routeId} THEN '{$boardingHtml}' ";
                // $droppingCase .= "WHEN {$routeId} THEN '{$droppingHtml}' ";
            }

            $idsString = implode(',', $ids);

            DB::statement("UPDATE mst_routes_details
                    SET
                        bus_count = CASE id {$busCase} END,
                        operators_count = CASE id {$operatorCase} END,
                        min_fare = CASE id {$minFareCase} END,
                        max_fare = CASE id {$maxFareCase} END,
                        first_bus_timing = CASE id {$firstBusCase} END,
                        last_bus_timing = CASE id {$lastBusCase} END,
                        duration_in_hours = CASE id {$durationCase} END,
                        is_operator_added = CASE id {$isOperatorAddedCase} END,
                        is_bus_type_added = CASE id {$isBusTypeAddedCase} END,
                        bus_type_comma_separaed = CASE id {$busTypeCommaSeparatedCases} END,
                        bus_type = CASE id {$busTypeCases} END
                        
                    WHERE id IN ({$idsString})
                ");
            // boarding_points_list = CASE id {$boardingCase} END,
            // dropping_points_list = CASE id {$droppingCase} END
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
}
