<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentSlider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class AgentSliderController extends Controller
{
    /**
     * Get Agent Slider list
     */
    public function index(Request $request)
    {
        try {

            $perPage = $request->get('per_page', 10);

            $query = AgentSlider::query();

            /*
         * Status filter
         */
            if (
                $request->has('status') &&
                $request->status !== ''
            ) {
                $query->where(
                    'status',
                    $request->status
                );
            }

            /*
         * Search
         */
            if (
                $request->has('searchBy') &&
                !empty($request->searchBy)
            ) {

                $search =
                    $request->searchBy;

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'alt_tag',
                        'LIKE',
                        '%' . $search . '%'
                    )

                        ->orWhere(
                            'slider_description',
                            'LIKE',
                            '%' . $search . '%'
                        )

                        ->orWhere(
                            'url',
                            'LIKE',
                            '%' . $search . '%'
                        );
                });
            }

            /*
         * Get sliders
         */
            $sliders = $query
                ->orderBy('sequence', 'asc')
                ->orderBy('id', 'desc')
                ->paginate($perPage);


            /*
         * =====================================================
         * GET USER NAMES FOR created_by
         * =====================================================
         */

            $createdByIds =
                $sliders->getCollection()
                ->pluck('created_by')
                ->filter()
                ->unique()
                ->values()
                ->toArray();


            $users = [];

            if (!empty($createdByIds)) {

                $users = DB::table('users')
                    ->whereIn(
                        'id',
                        $createdByIds
                    )
                    ->pluck(
                        'name',
                        'id'
                    )
                    ->toArray();
            }


            /*
         * =====================================================
         * ADD created_by_name TO EACH SLIDER
         * =====================================================
         */

            $sliders
                ->getCollection()
                ->transform(function ($slider) use ($users) {

                    $createdById =
                        $slider->created_by;

                    $slider->created_by_name =
                        isset($users[$createdById])
                        ? $users[$createdById]
                        : null;

                    return $slider;
                });


            return response()->json([
                'status' => true,
                'data' => $sliders
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'slider_img' => 'required|image|mimes:jpg,jpeg,png|max:100',
                'alt_tag' => 'required|string|max:255',
                'slider_description' => 'nullable|string',
                'url' => 'nullable|string|max:1000',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'default_slider' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }


            /*
             * If this slider is marked as default,
             * remove default from existing sliders.
             */
            $defaultSlider = $request->default_slider == 1 ||
                $request->default_slider === true ||
                $request->default_slider === 'true';

            if ($defaultSlider) {
                AgentSlider::where('default_slider', 1)
                    ->update([
                        'default_slider' => 0,
                        'updated_at' => now()
                    ]);
            }


            $maxSequence = AgentSlider::max('sequence');
            $sequence = $maxSequence ? $maxSequence + 1 : 1;
            $randomName = Str::random(32);
            $image = $request->file('slider_img');
            $folder = 'agent_slider';
            $storagePath = storage_path('app/public/' . $folder);

            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            $fileName = $randomName . '.webp';
            $fullPath = $storagePath . '/' . $fileName;
            $imageInfo = getimagesize($image->getRealPath());

            if (!$imageInfo) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid image file.'
                ], 422);
            }

            switch ($imageInfo['mime']) {

                case 'image/jpeg':
                    $sourceImage = imagecreatefromjpeg($image->getRealPath());
                    break;

                case 'image/png':
                    $sourceImage = imagecreatefrompng($image->getRealPath());

                    // Preserve PNG transparency
                    imagepalettetotruecolor($sourceImage);
                    imagealphablending($sourceImage, false);
                    imagesavealpha($sourceImage, true);

                    break;

                default:
                    return response()->json([
                        'status' => false,
                        'message' => 'Only JPG, JPEG and PNG images are allowed.'
                    ], 422);
            }


            /*
             * Save as WebP
             */
            imagewebp(
                $sourceImage,
                $fullPath,
                85
            );

            imagedestroy($sourceImage);


            /*
             * Save database record
             */
            $slider = AgentSlider::create([
                'url' => $request->url,
                'image_path' => 'storage/' . $folder . '/',
                'alt_tag' => $request->alt_tag,
                'slider_description' => $request->slider_description,
                'file_name' => $fileName,
                'default_slider' => $defaultSlider ? 1 : 0,
                'sequence' => $sequence,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => $request->created_by,
            ]);


            return response()->json([
                'status' => true,
                'message' => 'Agent slider added successfully.',
                'data' => $slider
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $slider = AgentSlider::find($id);

        if (!$slider) {
            return response()->json([
                'status' => false,
                'message' => 'Slider not found.'
            ], 404);
        }

        // Get creator's name from users table
        $user = DB::table('user')
            ->where('id', $slider->created_by)
            ->first();

        // Add user name to slider response
        $slider->created_by_name = $user
            ? $user->name
            : null;

        return response()->json([
            'status' => true,
            'data' => $slider
        ]);
    }

    public function update(Request $request, $id)
    {
        try {

            $slider = AgentSlider::find($id);

            if (!$slider) {
                return response()->json([
                    'status' => false,
                    'message' => 'Slider not found.'
                ], 404);
            }


            $validator = Validator::make($request->all(), [
                'alt_tag' => 'required|string|max:255',
                'slider_description' => 'nullable|string',
                'url' => 'nullable|string|max:1000',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'slider_img' => 'nullable|image|mimes:jpg,jpeg,png|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }


            $defaultSlider = $request->default_slider == 1 ||
                $request->default_slider === true ||
                $request->default_slider === 'true';


            /*
             * Remove default from other sliders
             */
            if ($defaultSlider) {

                AgentSlider::where('id', '!=', $id)
                    ->where('default_slider', 1)
                    ->update([
                        'default_slider' => 0,
                        'updated_at' => now()
                    ]);
            }


            /*
             * Update image if a new image was uploaded
             */
            if ($request->hasFile('slider_img')) {

                $image = $request->file('slider_img');

                $folder = 'agent_slider';

                $storagePath = storage_path('app/public/' . $folder);

                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0755, true);
                }

                /*
                 * Delete old image
                 */
                $oldFile = $storagePath . '/' . $slider->file_name;

                if ($slider->file_name && file_exists($oldFile)) {
                    unlink($oldFile);
                }


                /*
                 * Generate new 32 character name
                 */
                $randomName = Str::random(32);

                $fileName = $randomName . '.webp';

                $fullPath = $storagePath . '/' . $fileName;


                /*
                 * Convert image
                 */
                $imageInfo = getimagesize($image->getRealPath());

                switch ($imageInfo['mime']) {

                    case 'image/jpeg':
                        $sourceImage = imagecreatefromjpeg($image->getRealPath());
                        break;

                    case 'image/png':
                        $sourceImage = imagecreatefrompng($image->getRealPath());

                        imagepalettetotruecolor($sourceImage);
                        imagealphablending($sourceImage, false);
                        imagesavealpha($sourceImage, true);

                        break;

                    default:
                        return response()->json([
                            'status' => false,
                            'message' => 'Only JPG, JPEG and PNG images are allowed.'
                        ], 422);
                }


                imagewebp(
                    $sourceImage,
                    $fullPath,
                    85
                );

                imagedestroy($sourceImage);


                $slider->file_name = $fileName;
                $slider->image_path = 'storage/' . $folder . '/';
            }


            /*
             * Update fields
             */
            $slider->url = $request->url;
            $slider->alt_tag = $request->alt_tag;
            $slider->slider_description = $request->slider_description;
            $slider->default_slider = $defaultSlider ? 1 : 0;
            $slider->start_date = $request->start_date;
            $slider->end_date = $request->end_date;
            $slider->updated_at = now();

            $slider->save();


            return response()->json([
                'status' => true,
                'message' => 'Agent slider updated successfully.',
                'data' => $slider
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {

            $slider = AgentSlider::find($id);

            if (!$slider) {
                return response()->json([
                    'status' => false,
                    'message' => 'Slider not found.'
                ], 404);
            }


            /*
             * Delete image
             */
            $filePath = storage_path(
                'app/public/agent_slider/' . $slider->file_name
            );

            if ($slider->file_name && file_exists($filePath)) {
                unlink($filePath);
            }


            $slider->delete();


            return response()->json([
                'status' => true,
                'message' => 'Agent slider deleted successfully.'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function changeStatus($id)
    {
        try {

            $slider = AgentSlider::find($id);

            if (!$slider) {
                return response()->json([
                    'status' => false,
                    'message' => 'Slider not found.'
                ], 404);
            }

            $slider->status = $slider->status == 1 ? 0 : 1;
            $slider->updated_at = now();
            $slider->save();

            return response()->json([
                'status' => true,
                'message' => 'Slider status updated successfully.',
                'data' => $slider
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSequence(Request $request, $id)
    {
        try {

            $slider = AgentSlider::find($id);

            if (!$slider) {
                return response()->json([
                    'status' => false,
                    'message' => 'Slider not found.'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'sequence' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }

            $slider->sequence = $request->sequence;
            $slider->updated_at = now();
            $slider->save();

            return response()->json([
                'status' => true,
                'message' => 'Sequence updated successfully.',
                'data' => $slider
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
