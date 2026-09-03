<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AgentFaqController extends Controller
{

    public function getCategoryTypes()
    {
        try {

            $types = DB::table('agent_faq_category')
                ->where('status', 1)
                ->whereNotNull('type')
                ->where('type', '!=', '')
                ->select('type')
                ->distinct()
                ->orderBy('type')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $types
            ]);
        } catch (Exception $e) {

            Log::error(
                'Get Agent FAQ category types error: ' .
                    $e->getMessage()
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to load category types',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getCategoriesByType(Request $request)
    {
        try {

            $type = $request->type;

            $categories = DB::table('agent_faq_category')
                ->where('status', 1)
                ->where('type', $type)
                ->select(
                    'id',
                    'category_name',
                    'type'
                )
                ->orderBy('category_name')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $categories
            ]);
        } catch (Exception $e) {

            Log::error(
                'Get Agent FAQ categories error: ' .
                    $e->getMessage()
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to load categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getAll(Request $request)
    {
        try {
            $query = DB::table('agent_faq as faq')
                ->leftJoin(
                    'agent_faq_category as category',
                    'category.id',
                    '=',
                    'faq.category_id'
                )
                ->leftJoin(
                    'user as creator',
                    'creator.id',
                    '=',
                    'faq.created_by'
                )
                ->leftJoin(
                    'user as updater',
                    'updater.id',
                    '=',
                    'faq.updated_by'
                )
                ->select(
                    'faq.id',
                    'faq.type_id',
                    'faq.category_id',
                    'faq.faq_name',
                    'faq.question',
                    'faq.answer',
                    'faq.status',
                    'faq.created_at',
                    'faq.created_by',
                    'faq.updated_at',
                    'faq.updated_by',
                    'category.category_name',
                    'category.type as category_type',   
                    'creator.name as created_by_name',
                    'updater.name as updated_by_name'
                );

            if ($request->filled('category_type')) {
                $query->where('category.type', $request->category_type);
            }

            if ($request->filled('category_id')) {
                $query->where('faq.category_id', $request->category_id);
            }

            if ($request->filled('faq_search')) {
                $search = trim($request->faq_search);

                $query->where(function ($q) use ($search) {
                    $q->where('faq.faq_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('faq.question', 'LIKE', '%' . $search . '%')
                        ->orWhere('faq.answer', 'LIKE', '%' . $search . '%');
                });
            }

            $query->orderBy('faq.id', 'desc');

            $perPage = (int) $request->input('rows_number', 10);

            if ($perPage <= 0) {
                $perPage = 10;
            }

            if ($perPage > 100) {
                $perPage = 100;
            }

            $data = $query->paginate($perPage);

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {

            Log::error('Get Agent FAQ error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Unable to load Agent FAQ',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getFaq($id)
    {
        try {

            $faq = DB::table('agent_faq as faq')
                ->leftJoin(
                    'agent_faq_category as category',
                    'category.id',
                    '=',
                    'faq.category_id'
                )
                ->where('faq.id', $id)
                ->select(
                    'faq.id',
                    'faq.type_id',
                    'faq.category_id',
                    'faq.faq_name',
                    'faq.question',
                    'faq.answer',
                    'faq.status',
                    'faq.created_at',
                    'faq.created_by',
                    'faq.updated_at',
                    'faq.updated_by',
                    'category.category_name',
                    'category.type as category_type'
                )

                ->first();


            if (!$faq) {

                return response()->json([
                    'status' => false,
                    'message' => 'Agent FAQ not found'
                ], 404);
            }


            return response()->json([
                'status' => true,
                'data' => $faq
            ]);
        } catch (Exception $e) {

            Log::error(
                'Get Agent FAQ details error: ' .
                    $e->getMessage()
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to load Agent FAQ',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function addFaq(Request $request)
    {
        try {

            $request->validate([
                'type_id' => 'required|integer|in:1,2',
                'category_id' => 'required|integer',
                'faq_name' => 'required|string|max:255',
                'question' => 'required|string',
                'answer' => 'required|string',
                'status' => 'nullable|in:0,1'
            ]);

            $category = DB::table('agent_faq_category')
                ->where('id', $request->category_id)
                ->where('status', 1)
                ->first();

            if (!$category) {

                return response()->json([
                    'status' => false,
                    'message' => 'Selected FAQ category is invalid or inactive'
                ], 422);
            }

            $now = now();

            $id = DB::table('agent_faq')->insertGetId([

                'type_id' => $request->type_id,
                'category_id' => $request->category_id,
                'faq_name' => $request->faq_name,
                'question' => $request->question,
                'answer' => $request->answer,
                'status' => $request->has('status') ? $request->status : 1,
                'created_by' => $request->created_by,
                'created_at' => $now,
                'updated_at' => $now,
                'updated_by' => $request->created_by
            ]);


            return response()->json([
                'status' => true,
                'message' => 'Agent FAQ added successfully',
                'id' => $id
            ], 200);
        } catch (Exception $e) {

            Log::error(
                'Add Agent FAQ error: ' . $e->getMessage(),
                [
                    'request' => $request->all()
                ]
            );

            return response()->json([
                'status' => false,
                'message' => 'Unable to add Agent FAQ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateFaq(
        Request $request,
        $id
    ) {

        try {

            $request->validate([
                'type_id' => 'required|integer|in:1,2',
                'category_id' => 'required|integer',
                'faq_name' => 'required|string|max:255',
                'question' => 'required|string',
                'answer' => 'required|string',
                'status' => 'nullable|in:0,1'
            ]);


            $faq =
                DB::table('agent_faq')
                ->where(
                    'id',
                    $id
                )
                ->first();


            if (!$faq) {

                return response()->json([
                    'status' => false,
                    'message' =>
                    'Agent FAQ not found'
                ], 404);
            }

            $category = DB::table('agent_faq_category')
                ->where('id', $request->category_id)
                ->where('type_id', $request->type_id)
                ->where('status', 1)
                ->first();

            if (!$category) {

                return response()->json([
                    'status' => false,
                    'message' =>
                    'Selected FAQ category is invalid or inactive'
                ], 422);
            }


            DB::table('agent_faq')
                ->where(
                    'id',
                    $id
                )
                ->update([
                    'category_id' => $request->category_id,
                    'faq_name' => $request->faq_name,
                    'question' => $request->question,
                    'answer' => $request->answer,
                    'status' => $request->has('status') ? $request->status : $faq->status,
                    'updated_by' => $request->updated_by,
                    'updated_at' =>
                    now()

                ]);


            return response()->json([
                'status' => true,
                'message' =>
                'Agent FAQ updated successfully',
                'id' => $id
            ], 200);
        } catch (Exception $e) {

            Log::error(
                'Update Agent FAQ error: ' .
                    $e->getMessage(),
                [
                    'faq_id' => $id,
                    'request' =>
                    $request->all()
                ]
            );

            return response()->json([
                'status' => false,
                'message' =>
                'Unable to update Agent FAQ',
                'error' =>
                $e->getMessage()
            ], 500);
        }
    }


    public function changeStatus(
        Request $request,
        $id
    ) {

        try {

            $faq =
                DB::table('agent_faq')
                ->where(
                    'id',
                    $id
                )
                ->first();


            if (!$faq) {

                return response()->json([
                    'status' => false,
                    'message' =>
                    'Agent FAQ not found'
                ], 404);
            }


            $newStatus =
                $faq->status == 1
                ? 0
                : 1;


            DB::table('agent_faq')
                ->where(
                    'id',
                    $id
                )
                ->update([

                    'status' =>
                    $newStatus,

                    'updated_at' =>
                    now(),

                    'updated_by' =>
                    $request->updated_by

                ]);


            return response()->json([

                'status' => true,

                'message' =>
                'FAQ status changed successfully',

                'data' => [

                    'id' =>
                    $id,

                    'status' =>
                    $newStatus

                ]

            ]);
        } catch (Exception $e) {

            Log::error(
                'Agent FAQ status error: ' .
                    $e->getMessage()
            );

            return response()->json([
                'status' => false,
                'message' =>
                'Unable to change FAQ status',
                'error' =>
                $e->getMessage()
            ], 500);
        }
    }
}
