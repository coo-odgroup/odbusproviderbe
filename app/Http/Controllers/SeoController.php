<?php

namespace App\Http\Controllers;

use App\Models\BoardingDroping;
use App\Models\Bus;
use App\Models\BusSchedule;
use App\Models\BusScheduleDate;
use App\Models\CityContent;
use App\Models\Location;
use App\Models\RouteDetail;
use App\Models\RouteMap;
use App\Models\SeoContent;
use App\Models\TicketPrice;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SeoController extends Controller
{
    use ApiResponser;

    public function cityContent(Request $request)
    {
        try {
            $query = CityContent::join('location', 'location.id', '=', 'mst_city_content.city_id')
                ->select(
                    'mst_city_content.id',
                    'mst_city_content.city_id',
                    'mst_city_content.content',
                    'location.name'
                );

            // 🔍 Location-wise search (by name or id)
            if (!empty($request->location)) {
                $query->where('location.name', 'LIKE', '%' . $request->location . '%');
            }

            $limit = $request->limit ?? 5;

            $data = $query->limit($limit)->get();

            return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function UpdateContent(Request $request)
    {
        try {
            $id = $request->id;
            $data = [
                "content" => $request->content,
                "updated_by" => 1
            ];
            $updatedata = CityContent::where('id', $id)->update($data);
            return $this->successResponse($updatedata, "Updated Successfully..", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getRoutes()
    {
        try {
            $data = RouteDetail::where('active_status', 1)
                ->select(
                    'id',
                    'source',
                    'destination',
                    DB::raw("CONCAT(source, ' - ', destination) as route_name")
                )
                ->get();

            return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    // public function getLocation(Request $request)
    // {
    //     try {
    //         $id = $request->route_id;
    //         $data = RouteMap::where('parent_route_id', $id)
    //             ->join('mst_routes_details as r', 'r.id', '=', 'mst_route_map.route_id')
    //             ->select(
    //                 'r.id',
    //                 'r.source_id',
    //                 'r.destination_id',
    //                 'r.source',
    //                 'r.destination',
    //                 'r.distance',
    //             )
    //             ->get();

    //         // Prepare reverse conditions
    //         $reverseConditions = [];
    //         foreach ($data as $route) {
    //             $reverseConditions[] = [
    //                 'source_id' => $route->destination_id,
    //                 'destination_id' => $route->source_id
    //             ];
    //         }

    //         // Fetch reverse routes
    //         $reverseRoutes = RouteDetail::where(function ($query) use ($reverseConditions) {
    //             foreach ($reverseConditions as $cond) {
    //                 $query->orWhere(function ($q) use ($cond) {
    //                     $q->where('source_id', $cond['source_id'])
    //                         ->where('destination_id', $cond['destination_id']);
    //                 });
    //             }
    //         })
    //             ->select('id', 'source_id', 'destination_id', 'source', 'destination', 'distance')
    //             ->get();

    //         // Merge & remove duplicates
    //         $finalData = $data->merge($reverseRoutes)->unique('id')->values();

    //         $grouped = collect($finalData)->groupBy(function ($item) {
    //             $ids = [$item['source_id'], $item['destination_id']];
    //             sort($ids); // ensures same key for forward & reverse
    //             return implode('-', $ids);
    //         });

    //         $result = $grouped->map(function ($items) {
    //             return $items->values(); // gives pair (forward + reverse)
    //         })->values();

    //         return $this->successResponse($result, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()]);
    //     }
    // }

    public function getLocation(Request $request)
    {
        try {
            // ROUTE ID SEARCH
            if ($request->filled('route_id')) {

                $id = $request->route_id;

                $data = RouteMap::where('parent_route_id', $id)
                    ->join('mst_routes_details as r', 'r.id', '=', 'mst_route_map.route_id')
                    ->select(
                        'r.id',
                        'r.source_id',
                        'r.destination_id',
                        'r.source',
                        'r.destination',
                        'r.distance'
                    )
                    ->get();

                // Find reverse routes
                $reverseConditions = [];

                foreach ($data as $route) {
                    $reverseConditions[] = [
                        'source_id' => $route->destination_id,
                        'destination_id' => $route->source_id
                    ];
                }

                $reverseRoutes = RouteDetail::where(function ($query) use ($reverseConditions) {
                    foreach ($reverseConditions as $cond) {
                        $query->orWhere(function ($q) use ($cond) {
                            $q->where('source_id', $cond['source_id'])
                                ->where('destination_id', $cond['destination_id']);
                        });
                    }
                })
                    ->select(
                        'id',
                        'source_id',
                        'destination_id',
                        'source',
                        'destination',
                        'distance'
                    )
                    ->get();

                $finalData = $data->merge($reverseRoutes)
                    ->unique('id')
                    ->values();

                $grouped = $finalData->groupBy(function ($item) {
                    $ids = [$item->source_id, $item->destination_id];
                    sort($ids);
                    return implode('-', $ids);
                });

                $result = $grouped->map(function ($items) {

                    $items = $items->values();

                    if ($items->count() == 1) {
                        $items->push((object)[
                            'id' => null,
                            'source_id' => null,
                            'destination_id' => null,
                            'source' => '',
                            'destination' => '',
                            'distance' => $items[0]->distance
                        ]);
                    }

                    return $items;
                })->values();

                return $this->successResponse(
                    $result,
                    Config::get('constants.RECORD_FETCHED'),
                    Response::HTTP_OK
                );
            }

            // LOCATION ID SEARCH
            if ($request->filled('location_id')) {

                $locationId = $request->location_id;

                $data = RouteDetail::where(function ($q) use ($locationId) {
                    $q->where('source_id', $locationId)
                        ->orWhere('destination_id', $locationId);
                })
                    ->select(
                        'id',
                        'source_id',
                        'destination_id',
                        'source',
                        'destination',
                        'distance'
                    )
                    ->get();

                $grouped = $data->groupBy(function ($item) {
                    $ids = [$item->source_id, $item->destination_id];
                    sort($ids);
                    return implode('-', $ids);
                });

                $result = $grouped->map(function ($items) {

                    $items = $items->values();

                    if ($items->count() == 1) {
                        $items->push((object)[
                            'id' => null,
                            'source_id' => null,
                            'destination_id' => null,
                            'source' => '',
                            'destination' => '',
                            'distance' => $items[0]->distance
                        ]);
                    }

                    return $items;
                })->values();

                return $this->successResponse(
                    $result,
                    Config::get('constants.RECORD_FETCHED'),
                    Response::HTTP_OK
                );
            }

            return response()->json([
                'message' => 'route_id or location_id is required'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }


    public function updateDistance(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data)) {
                return response()->json(['message' => 'No data found'], 400);
            }

            $ids = [];
            $cases = [];

            $updatedBy = $data[0]['user_id'] ?? null;
            $now = Carbon::now()->toDateTimeString();

            foreach ($data as $item) {
                $id = (int) $item['id'];
                $distance = addslashes($item['distance']);

                $ids[] = $id;
                $cases[] = "WHEN id = $id THEN '$distance'";
            }

            $idsString = implode(',', $ids);
            $casesString = implode(' ', $cases);

            DB::statement("
            UPDATE mst_routes_details
            SET 
                distance = CASE
                    $casesString
                END,
                updated_at = '$now',
                updated_by = '$updatedBy'
            WHERE id IN ($idsString)
        ");

            return response()->json([
                'message' => 'Distance updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function brd_drp(Request $request)
    {
        $location = RouteDetail::find($request->route_id);

        if (!$location) {
            return response()->json(['message' => 'Route not found'], 404);
        }

        $seoTemplate = DB::table('mst_seo_templates')->find(1);

        $source = $location->source;
        $destination = $location->destination;
        $min_fare = $location->min_fare;
        $max_fare = $location->max_fare;
        $distance = $location->distance ?? 0;
        $bus_type = $location->bus_type_comma_separaed;
        $source_slug = strtolower($source);
        $destination_slug = strtolower($destination);

        $duration = explode('-', $location->duration_in_hours);
        $from_hrs = $duration[0];
        $to_hrs = $duration[1];

        function replacePlaceholders($data, $replaceMap)
        {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = replacePlaceholders($value, $replaceMap);
                } else {
                    $data[$key] = str_replace(
                        array_keys($replaceMap),
                        array_values($replaceMap),
                        $value
                    );
                }
            }
            return $data;
        }

        $replaceMap = [
            '{{source}}'      => $source,
            '{{destination}}' => $destination,
            '{{from_hrs}}'    => $from_hrs,
            '{{to_hrs}}'      => $to_hrs,
            '{{bus_types}}'   => $bus_type,
            '{{min_fare}}'    => $min_fare,
            '{{max_fare}}'    => $max_fare,
            '{{distance}}'    => $distance,
            '{{source_slug}}' => $source_slug,
            '{{destination_slug}}' => $destination_slug,
        ];

        $breadcrumb = json_decode($seoTemplate->breadcrumb_schema, true);
        $breadcrumbFinal = replacePlaceholders($breadcrumb, $replaceMap);

        $faq = json_decode($seoTemplate->faq_schema, true);
        $faqFinal = replacePlaceholders($faq, $replaceMap);

        // return response()->json([
        //     'breadcrumb_schema' => $breadcrumbFinal,
        //     'faq_schema' => $faqFinal
        // ]);

        if (!$location) {
            return response()->json([
                'status' => false,
                'message' => 'Route not found'
            ], 404);
        }

        $source_id = $location->source_id;
        $destination_id = $location->destination_id;

        // Get selected (mapped) IDs
        $selectedPoints = DB::table('mst_route_brd_drp')
            ->where('route_id', $request->route_id)
            ->pluck('brd_drp_id')
            ->toArray();

        // Get all boarding/dropping points
        $points = BoardingDroping::whereIn('location_id', [$source_id, $destination_id])
            ->get()
            ->map(function ($item) use ($selectedPoints) {
                $item->checked = in_array($item->id, $selectedPoints);
                return $item;
            })
            ->groupBy('location_id');

        return response()->json([
            'status' => true,
            'boarding' => [
                'id' => $source_id,
                'name' => $location->source,
                'points' => $points[$source_id] ?? []
            ],
            'dropping' => [
                'id' => $destination_id,
                'name' => $location->destination,
                'points' => $points[$destination_id] ?? []
            ],
            'breadcrumb_schema' => $breadcrumbFinal,
            'faq_schema' => $faqFinal
        ]);
    }

    public function addbrd_drp(Request $request)
    {
        try {
            $payload = $request->payload;
            $schemaData = $request->schema;

            if (empty($payload)) {
                return response()->json(['message' => 'No data found'], 400);
            }

            $route_id = $payload[0]['route_id'];

            $schema = [
                "breadcrumb_schema" => json_encode($schemaData["breadcrumb_schema"] ?? null),
                "faq_schema"        => json_encode($schemaData["faq_schema"] ?? null),
                "service_schema"    => json_encode($schemaData["service_schema"] ?? null),
            ];

            DB::beginTransaction();

            RouteDetail::where('id', $route_id)->update($schema);

            DB::table('mst_route_brd_drp')
                ->where('route_id', $route_id)
                ->delete();

            $now = now();

            $finalData = array_map(function ($item) use ($now) {
                return [
                    'route_id'      => $item['route_id'],
                    'type'          => $item['type'],
                    'brd_drp_id'    => $item['brd_drp_id'],
                    'active_status' => 1,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                    'created_by'    => $item['created_by'] ?? null,
                    'updated_by'    => $item['updated_by'] ?? null,
                ];
            }, $payload);

            DB::table('mst_route_brd_drp')->insert($finalData);

            DB::commit();

            return response()->json([
                'message' => 'Data updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function seoContent(Request $request)
    // {
    //     $route_id = $request->route_id;
    //     $route_details = RouteDetail::find($route_id);

    //     $operator = DB::table('mst_routes_operators')->join('bus_operator', 'bus_operator.id', '=', 'mst_routes_operators.operator_id')
    //         ->where('mst_routes_operators.route_id', $route_id)
    //         ->where('active_status', 1)
    //         ->select('mst_routes_operators.route_id', 'mst_routes_operators.url_genrated', 'mst_routes_operators.operator_id', 'bus_operator.organisation_name')
    //         ->get();



    //     if ($route_details->bus_count == null) {
    //         $bus_count = $this->busCount(new Request(['route_id' => $route_id]));
    //         $route_details->update(['bus_count' => $bus_count]);
    //     } else {
    //         $bus_count = $route_details->bus_count;
    //     }

    //     $bordingdroping = DB::table('mst_route_brd_drp')
    //         ->join('boarding_droping', 'boarding_droping.id', '=', 'mst_route_brd_drp.brd_drp_id')
    //         ->select('mst_route_brd_drp.*', 'boarding_droping.boarding_point')
    //         ->where('route_id', $route_id);


    //     $source_content = CityContent::where('city_id', $route_details->source_id)->first();
    //     $destination_content = CityContent::where('city_id', $route_details->destination_id)->first();


    //     $template = DB::table('mst_seo_templates')->find(1);

    //     $meta_title = $template->meta_title;
    //     $meta_description = $template->meta_description;



    //     // Clone query
    //     $bordingpoint = (clone $bordingdroping)->where('type', 1)->get();
    //     $dropingpoint = (clone $bordingdroping)->where('type', 2)->get();

    //     $source = $route_details->source;
    //     $destination = $route_details->destination;
    //     $first_bus_timing = date('h:i A', strtotime($route_details->first_bus_timing));
    //     $last_bus_timing = date('h:i A', strtotime($route_details->last_bus_timing));
    //     $duration = str_replace('-', ' to ', $route_details->duration_in_hours);
    //     $min_fare = "₹" . $route_details->min_fare;
    //     $max_fare = "₹" . $route_details->max_fare;
    //     $bus_types = $route_details->bus_type_comma_separaed;
    //     $operators = $operator;
    //     $boarding_points = $bordingpoint;
    //     $droping_points = $dropingpoint;
    //     $price_range = "₹" . $min_fare . ' - ' . "₹" . $max_fare;

    //     $return_journey = "http://localhost:4200/routes/" . strtolower($destination) . "-" . strtolower($source) . "-bus-services";
    //     $booking_url = "http://localhost:4200/routes/" . strtolower($source) . "-" . strtolower($destination) . "-bus-services";

    //     // $operator_list = $operators->pluck('organisation_name')->implode(', ');
    //     $operator_list = $operators->map(function ($item) {
    //         return '<li><a href="' . $item->url_genrated . '" target="_blank">'
    //             . e($item->organisation_name) .
    //             '</a></li>';
    //     })->implode('');

    //     $boarding_points_list = $bordingpoint->map(function ($item) {
    //         return '<li>' . e($item->boarding_point) . '</li>';
    //     })->implode('');


    //     $dropping_points_list = $dropingpoint->map(function ($item) {
    //         return '<li>' . e($item->boarding_point) . '</li>';
    //     })->implode('');

    //     //For Meta Title
    //     //----------------------------------------------------

    //     $replacemetaData = [
    //         '{{source}}' => $source,
    //         '{{destination}}' => $destination
    //     ];


    //     $finalMetaTitle = str_replace(
    //         array_keys($replacemetaData),
    //         array_values($replacemetaData),
    //         $meta_title
    //     );

    //     //-------------------------------------------------------

    //     //For Meta Description
    //     $replacemetaDescData = [
    //         '{{source}}' => $source,
    //         '{{destination}}' => $destination,
    //         '{{bus_type_comma_separated}}' => $bus_types,
    //         '{{min_fare}}' => $min_fare,
    //     ];


    //     $finalMetaDescription = str_replace(
    //         array_keys($replacemetaDescData),
    //         array_values($replacemetaDescData),
    //         $meta_description
    //     );
    //     //------------------------------------------------------- 

    //     $replaceData = [
    //         '{{source}}' => $source,
    //         '{{destination}}' => $destination,
    //         '{{first_bus_timing}}' => $first_bus_timing,
    //         '{{last_bus_timing}}' => $last_bus_timing,
    //         '{{duration}}' => $duration,
    //         '{{distance}}' => $route_details->distance,
    //         '{{min_fare}}' => $min_fare,
    //         '{{max_fare}}' => $max_fare,
    //         '{{bus_types}}' => $bus_types,
    //         '{{operator_list}}' => $operator_list,
    //         '{{operators_count}}' => $operator->count(),
    //         '{{boarding_points_list}}' => $boarding_points_list,
    //         '{{bus_count}}' => $bus_count ?? 0,
    //         '{{dropping_points_list}}' => $dropping_points_list,
    //         '{{source_content}}' => $source_content->content ?? '',
    //         '{{destination_content}}' => $destination_content->content ?? '',
    //         '{{price_range}}' => $price_range,
    //         '{{return_journey}}' => $return_journey,
    //         '{{booking_url}}' => $booking_url,
    //     ];

    //     $templateContent = $template->content;


    //     $finalContent = str_replace(
    //         array_keys($replaceData),
    //         array_values($replaceData),
    //         $templateContent
    //     );

    //     $breadcrumb_schema = json_decode(json_decode($route_details->breadcrumb_schema, true), true);
    //     $faq_schema = json_decode(json_decode($route_details->faq_schema, true), true);

    //     return response()->json([
    //         'seo_content' => $finalContent,
    //         'breadcrumb_schema' => $breadcrumb_schema,
    //         'faq_schema' => $faq_schema,
    //         'meta_title' => $finalMetaTitle,
    //         'meta_description' => $finalMetaDescription,

    //     ]);
    // }


    public function seoContent(Request $request)
    {
        $route_id = $request->route_id;

        $route_details = RouteDetail::find($route_id);

        $operator = DB::table('mst_routes_operators')
            ->join('bus_operator', 'bus_operator.id', '=', 'mst_routes_operators.operator_id')
            ->where('mst_routes_operators.route_id', $route_id)
            ->where('active_status', 1)
            ->select(
                'mst_routes_operators.route_id',
                'mst_routes_operators.url_genrated',
                'mst_routes_operators.operator_id',
                'bus_operator.organisation_name'
            )
            ->get();

        if ($route_details->bus_count == null) {

            $bus_count = $this->busCount(
                new Request(['route_id' => $route_id])
            );

            $route_details->update([
                'bus_count' => $bus_count
            ]);
        } else {

            $bus_count = $route_details->bus_count;
        }

        $bordingdroping = DB::table('mst_route_brd_drp')
            ->join(
                'boarding_droping',
                'boarding_droping.id',
                '=',
                'mst_route_brd_drp.brd_drp_id'
            )
            ->select(
                'mst_route_brd_drp.*',
                'boarding_droping.boarding_point'
            )
            ->where('route_id', $route_id);

        $bordingpoint = (clone $bordingdroping)
            ->where('type', 1)
            ->get();

        $dropingpoint = (clone $bordingdroping)
            ->where('type', 2)
            ->get();

        $source_content = CityContent::where(
            'city_id',
            $route_details->source_id
        )->first();

        $destination_content = CityContent::where(
            'city_id',
            $route_details->destination_id
        )->first();

        $template = DB::table('mst_seo_templates')->find(1);

        $meta_title = $template->meta_title;
        $meta_description = $template->meta_description;

        $source = $route_details->source;
        $destination = $route_details->destination;

        $first_bus_timing = date(
            'h:i A',
            strtotime($route_details->first_bus_timing)
        );

        $last_bus_timing = date(
            'h:i A',
            strtotime($route_details->last_bus_timing)
        );

        $duration = str_replace(
            '-',
            ' to ',
            $route_details->duration_in_hours
        );

        $min_fare = "₹" . $route_details->min_fare;
        $max_fare = "₹" . $route_details->max_fare;

        $bus_types = $route_details->bus_type_comma_separaed;

        $price_range = $min_fare . ' - ' . $max_fare;

        $return_journey = Config::get('constants.BASE_URL') . "routes/" . strtolower($destination) . "-" . strtolower($source) . "-bus-services";
        $booking_url = Config::get('constants.BASE_URL') . "routes/" . strtolower($source) . "-" . strtolower($destination) . "-bus-services";

        $operator_list = $operator->map(function ($item) {

            return '<li>
                    <a href="' . $item->url_genrated . '" target="_blank">
                        ' . e($item->organisation_name) . '
                    </a>
                </li>';
        })->implode('');

        $boarding_points_list = $bordingpoint->map(function ($item) {

            return '<li>' . e($item->boarding_point) . '</li>';
        })->implode('');

        $dropping_points_list = $dropingpoint->map(function ($item) {

            return '<li>' . e($item->boarding_point) . '</li>';
        })->implode('');

        /* META TITLE */
        $replacemetaData = [
            '{{source}}' => $source,
            '{{destination}}' => $destination
        ];

        $finalMetaTitle = str_replace(
            array_keys($replacemetaData),
            array_values($replacemetaData),
            $meta_title
        );

        /* META DESCRIPTION */
        $replacemetaDescData = [
            '{{source}}' => $source,
            '{{destination}}' => $destination,
            '{{min_fare}}' => $min_fare,
        ];

        $finalMetaDescription = str_replace(
            array_keys($replacemetaDescData),
            array_values($replacemetaDescData),
            $meta_description
        );

        /* TEMPLATE CONTENT */
        $templateContent = $template->content;

        if (empty($source_content) || empty($source_content->content)) {

            $templateContent = preg_replace(
                '/<h2>Know More About \{\{source\}\}<\/h2>\s*<p>\{\{source_content\}\}<\/p>/is',
                '',
                $templateContent
            );
        }


        if (empty($destination_content) || empty($destination_content->content)) {

            $templateContent = preg_replace(
                '/<h2>Know More About \{\{destination\}\}<\/h2>\s*<p>\{\{destination_content\}\}<\/p>/is',
                '',
                $templateContent
            );
        }

        $replaceData = [
            '{{source}}' => $source,
            '{{destination}}' => $destination,
            '{{first_bus_timing}}' => $first_bus_timing,
            '{{last_bus_timing}}' => $last_bus_timing,
            '{{duration}}' => $duration,
            '{{distance}}' => $route_details->distance,
            '{{min_fare}}' => $min_fare,
            '{{max_fare}}' => $max_fare,
            '{{bus_types}}' => $bus_types,
            '{{operator_list}}' => $operator_list,
            '{{operators_count}}' => $operator->count(),
            '{{boarding_points_list}}' => $boarding_points_list,
            '{{bus_count}}' => $bus_count ?? 0,
            '{{dropping_points_list}}' => $dropping_points_list,
            '{{source_content}}' => $source_content->content ?? '',
            '{{destination_content}}' => $destination_content->content ?? '',
            '{{price_range}}' => $price_range,
            '{{return_journey}}' => $return_journey,
            '{{booking_url}}' => $booking_url,
        ];

        $finalContent = str_replace(
            array_keys($replaceData),
            array_values($replaceData),
            $templateContent
        );

        $breadcrumb_schema = json_decode(
            json_decode($route_details->breadcrumb_schema, true),
            true
        );

        $faq_schema = json_decode(
            json_decode($route_details->faq_schema, true),
            true
        );

        return response()->json([
            'seo_content' => $finalContent,
            'breadcrumb_schema' => $breadcrumb_schema,
            'faq_schema' => $faq_schema,
            'meta_title' => $finalMetaTitle,
            'meta_description' => $finalMetaDescription,
        ]);
    }


    public function addSeoContent(Request $request)
    {
        try {
            $route_id = $request->route_id;
            $content = $request->seo_content;
            $meta_title = $request->meta_title;
            $meta_description = $request->meta_description;
            $updated_by = $request->updated_by;

            $data = [
                'route_id' => $route_id,
                'content' => $content,
                'meta_title' => $meta_title,
                'meta_description' => $meta_description,
                'updated_by' => $updated_by,
                'is_publised' => 1,
            ];


            if (SeoContent::where('route_id', $route_id)->exists()) {
                SeoContent::where('route_id', $route_id)->update($data);
                $msg = 'SEO content updated successfully';
            } else {
                SeoContent::create($data);
                $msg = 'SEO content created successfully';
            }

            return response()->json([
                'message' => $msg
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function busCount(Request $request)
    {
        $route_id = $request->route_id;

        $location = RouteDetail::where('id', $route_id)->select('source_id', 'destination_id')->first();

        $exist_bus = TicketPrice::where('source_id', $location->source_id)
            ->where('destination_id', $location->destination_id)
            ->where('status', 1)
            ->distinct('bus_id')
            ->get('bus_id');


        $bus = Bus::whereIn('id', $exist_bus->pluck('bus_id'))
            ->where('status', 1)
            ->distinct('bus_number')
            ->get('id');


        $bus_schedule_date_count = BusScheduleDate::whereIn('bus_schedule_id', function ($query) use ($bus) {
            $query->select('id')
                ->from('bus_schedule')
                ->whereIn('bus_id', $bus->pluck('id'))
                ->where('status', 1);
        })
            ->where('status', 1)
            ->where('entry_date', '>=', date('Y-m-d'))
            ->distinct()
            ->count('bus_schedule_id');

        return $bus_schedule_date_count;
    }

    public function routeTemplate(Request $request)
    {
        $query = SeoContent::join(
            'mst_routes_details',
            'mst_routes_details.id',
            '=',
            'mst_seo_content.route_id'
        )
            ->select(
                'mst_seo_content.route_id',
                'mst_routes_details.source',
                'mst_routes_details.destination'
            );

        // Filter by route_id
        if ($request->filled('route_id')) {
            $query->where('mst_seo_content.route_id', $request->route_id);
        }

        $data = $query->get();

        return response()->json($data);
    }


    public function allRoute(Request $request)
    {
        $query = RouteDetail::select(
            'id',
            'source',
            'destination',
            'is_popular_routes',
            'is_top_routes',
            'sequence'
        );

        // Filter by route_id
        if ($request->filled('route_id')) {
            $query->where('id', $request->route_id);
        }

        // Filter by route type
        if ($request->filled('route_type')) {
            if ($request->route_type == 'is_popular_routes') {
                $query->where('is_popular_routes', 1);
            } elseif ($request->route_type == 'is_top_routes') {
                $query->where('is_top_routes', 1);
            }
        }

        // Order by sequence
        if ($request->filled('order_by') && in_array(strtolower($request->order_by), ['asc', 'desc'])) {
            $query->orderBy('sequence', $request->order_by);
        } else {
            $query->orderBy('sequence', 'asc');
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function templateDetails(Request $request)
    {
        $data = SeoContent::join(
            'mst_routes_details',
            'mst_routes_details.id',
            '=',
            'mst_seo_content.route_id'
        )
            ->select(
                'mst_seo_content.route_id',
                'mst_seo_content.meta_title',
                'mst_seo_content.meta_description',
                'mst_seo_content.content',
                'mst_routes_details.source',
                'mst_routes_details.destination',
                'mst_routes_details.breadcrumb_schema',
                'mst_routes_details.faq_schema'
            )
            ->where('mst_seo_content.route_id', $request->route_id)
            ->first();

        if ($data) {

            $data->breadcrumb_schema = json_decode(json_decode($data->breadcrumb_schema));

            $data->faq_schema = json_decode(json_decode($data->faq_schema));
        }

        return response()->json($data);
    }


    public function manageroute(Request $request)
    {
        // Get unique bus ids
        $busIds = TicketPrice::where('source_id', $request->source_id)
            ->where('destination_id', $request->destination_id)
            ->where('status', 1)
            ->pluck('bus_id')
            ->unique();

        $allRoutes = collect();

        foreach ($busIds as $busId) {

            $busRoutes = TicketPrice::where('bus_id', $busId)
                ->where('status', 1)
                ->select('source_id', 'destination_id')
                ->get();

            $allRoutes = $allRoutes->merge($busRoutes);
        }

        // Remove duplicate routes
        $uniqueRoutes = $allRoutes->unique(function ($item) {
            return $item->source_id . '-' . $item->destination_id;
        })->values();

        // Check route exists and get location names
        $finalData = $uniqueRoutes->map(function ($route) {

            $exists = RouteDetail::where('source_id', $route->source_id)
                ->where('destination_id', $route->destination_id)
                ->exists();

            // Get source name
            $sourceName = Location::where('id', $route->source_id)
                ->value('name');

            // Get destination name
            $destinationName = Location::where('id', $route->destination_id)
                ->value('name');

            return [
                'source_id'        => $route->source_id,
                'source_name'      => $sourceName,
                'destination_id'   => $route->destination_id,
                'destination_name' => $destinationName,
                'already_exist'    => $exists
            ];
        });

        return $finalData;
    }


    //Missing Routes
    public function getMissingRoutesByLocation(Request $request)
    {
        try {

            $locationId = $request->location_id;

            $data = DB::table('mst_routes_details as r1')
                ->leftJoin('mst_routes_details as r2', function ($join) {
                    $join->on('r1.source_id', '=', 'r2.destination_id')
                        ->on('r1.destination_id', '=', 'r2.source_id');
                })
                ->whereNull('r2.id')
                ->where(function ($query) use ($locationId) {
                    $query->where('r1.source_id', $locationId)
                        ->orWhere('r1.destination_id', $locationId);
                })
                ->select(
                    DB::raw('r1.destination_id as source_id'),
                    DB::raw('r1.source_id as destination_id'),
                    DB::raw('r1.destination as source'),
                    DB::raw('r1.source as destination'),
                    'r1.distance'
                )
                ->distinct()
                ->get();

            return $this->successResponse(
                $data,
                'Missing routes',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getRouteBuses(Request $request)
    {
        $sourceId = $request->source_id;
        $destinationId = $request->destination_id;

        $data = DB::table('ticket_price as tp')
            ->join('bus as b', 'b.id', '=', 'tp.bus_id')
            ->where('tp.source_id', $sourceId)
            ->where('tp.destination_id', $destinationId)
            ->where('tp.status', 1)
            ->where('b.status', 1)
            ->select(
                'tp.id',
                'tp.bus_id',
                'tp.base_seat_fare',
                'b.name',
                'b.bus_number'
            )
            ->get();

        return response()->json($data);
    }

    //check bus raw query
    /*
        SELECT tp.*
        FROM ticket_price tp
        LEFT JOIN bus b ON b.id = tp.bus_id
        WHERE tp.source_id = 32
        AND tp.destination_id = 98
        AND tp.status = 1
        AND b.status = 1;
    */
}
