<?php

namespace App\Http\Controllers;

use App\Models\Auther;
use App\Models\Author;
use App\Models\Blog;
use App\Models\Blogcategory;
use App\Models\Blogroute;
use App\Models\Tag;
use App\Models\Tagmap;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    use ApiResponser;

    //Blog category
    // ---------------------------------------------------------------------------------

    public function addCategory(Request $request)
    {
        try {

            $path = null;

            if ($request->hasFile('banner_image')) {
                $file = $request->banner_image;
                $filename = $file->getClientOriginalName();
                $picture = rand() . '-' . $filename;

                $file->move(public_path('uploads/blogs/category'), $picture);
                $path = "uploads/blogs/category/" . $picture;
            }

            $data = [
                "category_name" => $request->category_name,
                "slug" => $request->slug,
                "description" => $request->description,
                "icon" => $request->icon,
                "banner_image" => $path,
                "meta_title" => $request->meta_title,
                "meta_description" => $request->meta_description,
                "breadcrumb_schema" => $request->breadcrumb_schema,
                "created_by" => 1,
                "updated_by" => 1,
            ];

            Blogcategory::create($data);

            return $this->successResponse("Blog Category Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    // public function allCategory()
    // {
    //     $data = Blogcategory::all();
    //     return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    // }

    public function allCategory(Request $request)
    {
        $query = BlogCategory::query();

        // Search
        if ($request->searchBy != '') {

            $query->where('category_name', 'LIKE', '%' . $request->searchBy . '%')
                ->orWhere('slug', 'LIKE', '%' . $request->searchBy . '%');
        }

        // Status Filter
        if ($request->status !== '' && $request->status !== null) {

            $query->where('active_status', $request->status);
        }

        // Per Page
        $per_page = $request->per_page ?? 10;

        $data = $query->orderBy('id', 'DESC')
            ->paginate($per_page);

        return response()->json([
            'status' => 1,
            'message' => 'Record Fetched Successfully',
            'data' => $data->items(),
            'total' => $data->total()
        ]);
    }

    public function updatecategory(Request $request, $id)
    {
        try {

            $data = [
                "category_name" => $request->category_name,
                "slug" => $request->slug,
                "description" => $request->description,
                "icon" => $request->icon,
                "meta_title" => $request->meta_title,
                "meta_description" => $request->meta_description,
                "breadcrumb_schema" => $request->breadcrumb_schema,
                "updated_by" => 1,
            ];

            if ($request->hasFile('banner_image')) {

                $file = $request->file('banner_image');
                $filename = $file->getClientOriginalName();
                $picture = time() . '_' . $filename;

                $blogcat = Blogcategory::find($id);

                if ($blogcat && $blogcat->banner_image) {

                    $existing_image = public_path($blogcat->banner_image);

                    if (file_exists($existing_image)) {
                        unlink($existing_image);
                    }
                }

                $file->move(public_path('uploads/blogs/category'), $picture);

                $data['banner_image'] = "blogs/category/" . $picture;
            }

            Blogcategory::where('id', $id)->update($data);

            return $this->successResponse("Blog Category Updated", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletecategory($id)
    {
        try {

            $category = Blogcategory::find($id);

            $category->update([
                'deleted_by' => 1
            ]);

            $category->delete();

            return $this->successResponse("Blog Category Deleted", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeCategoryStatus(Request $request)
    {
        $category = BlogCategory::find($request->id);

        if (!$category) {
            return response()->json([
                'status' => 0,
                'message' => 'Category not found'
            ]);
        }

        $category->active_status =
            $category->active_status == 1 ? 0 : 1;

        $category->save();

        return response()->json([
            'status' => 1,
            'message' => 'Status updated successfully'
        ]);
    }


    //Blog
    // ----------------------------------------------------------------------------------
    public function addblog(Request $request)
    {
        // return $request->all();
        try {

            $path = null;
            $thumpath = null;
            $ogpath = null;

            // Featured Image
            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                $extension = $file->getClientOriginalExtension();
                $picture = time() . '_' . Str::random(20) . '.' . $extension;
                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $path = "blogs/blog_image/" . $picture;
            }


            // Thumb Image
            if ($request->hasFile('thumb_image')) {

                $file = $request->file('thumb_image');
                $extension = $file->getClientOriginalExtension();
                $picture = time() . '_' . Str::random(20) . '.' . $extension;
                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $thumpath = "blogs/blog_image/" . $picture;
            }


            // OG Image
            if ($request->hasFile('og_image')) {

                $file = $request->file('og_image');
                $extension = $file->getClientOriginalExtension();
                $picture = time() . '_' . Str::random(20) . '.' . $extension;
                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $ogpath = "blogs/blog_image/" . $picture;
            }

            $data = [
                "category_id" => $request->category_id,
                "title" => $request->title,
                "slug" => $request->slug,
                "short_description" => $request->short_description,
                "content" => $request->content,
                "featured_image" => $path,
                "feature_alt_text" => $request->feature_alt_text,
                "thumb_image" => $thumpath,
                "thumb_alt_text" => $request->thumb_alt_text,
                "author_id" => $request->author_id,
                "is_featured" => 0,
                "active_status" => 1,
                "meta_title" => $request->meta_title,
                "meta_description" => $request->meta_description,
                "meta_keywords" => $request->meta_keywords,
                "canonical_url" => $request->canonical_url,
                "og_title" => $request->og_title,
                "og_desc" => $request->og_desc,
                "og_image" => $ogpath,
                "breadcrumb_schema" => $request->breadcrumb_schema ? json_encode($request->breadcrumb_schema) : null,
                "faq_schema" => $request->faq_schema ? json_encode($request->faq_schema) : null,
                "service_schema" => $request->service_schema ? json_encode($request->service_schema) : null,
                "created_by" => 1,
                "updated_by" => 1,
            ];

            Blog::create($data);

            return $this->successResponse("Blog Added Successfully", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function allblog(Request $request)
    {
        $query = Blog::join('blog_categories', 'blog_categories.id', '=', 'blogs.category_id')
            ->select(
                'blogs.*',
                'blog_categories.category_name'
            );

        // Search
        if ($request->searchBy != '') {

            $query->where(function ($q) use ($request) {

                $q->where('blogs.title', 'LIKE', '%' . $request->searchBy . '%')
                    ->orWhere('blogs.slug', 'LIKE', '%' . $request->searchBy . '%')
                    ->orWhere('blog_categories.category_name', 'LIKE', '%' . $request->searchBy . '%');
            });
        }

        // Status filter
        if ($request->status !== '' && $request->status !== null) {
            $query->where('blogs.active_status', $request->status);
        }

        if ($request->category_id !== '' && $request->category_id !== null) {
            $query->where('blogs.category_id', $request->category_id);
        }

        if ($request->author_id !== '' && $request->author_id !== null) {
            $query->where('blogs.author_id', $request->author_id);
        }

        // Per page
        $per_page = $request->per_page ?? 10;

        $data = $query->orderBy('blogs.id', 'DESC')
            ->paginate($per_page);

        return response()->json([
            'status' => 1,
            'message' => 'Record Fetched Successfully',
            'data' => $data->items(),
            'total' => $data->total()
        ]);
    }


    public function updateblog(Request $request, $id)
    {
        try {

            $blog = Blog::find($id);

            if (!$blog) {
                return response()->json([
                    'status' => false,
                    'message' => 'Blog not found'
                ], 404);
            }

            $data = [
                "category_id" => $request->category_id,
                "title" => $request->title,
                "slug" => $request->slug,
                "short_description" => $request->short_description,
                "content" => $request->content,
                "feature_alt_text" => $request->feature_alt_text,
                "thumb_alt_text" => $request->thumb_alt_text,
                "author_id" => $request->author_id,
                "is_featured" => 0,
                "active_status" => 1,
                "meta_title" => $request->meta_title,
                "meta_description" => $request->meta_description,
                "meta_keywords" => $request->meta_keywords,
                "canonical_url" => $request->canonical_url,
                "og_title" => $request->og_title,
                "og_desc" => $request->og_desc,

                "breadcrumb_schema" => $request->breadcrumb_schema ? json_encode($request->breadcrumb_schema) : null,
                "faq_schema" => $request->faq_schema ? json_encode($request->faq_schema) : null,
                "service_schema" => $request->service_schema ? json_encode($request->service_schema) : null,

                "updated_by" => 1,
            ];

            // Featured Image
            if ($request->hasFile('featured_image')) {

                $file = $request->file('featured_image');
                $extension = $file->getClientOriginalExtension();
                $picture = time() . '_' . Str::random(20) . '.' . $extension;

                if ($blog->featured_image) {

                    $existing_image = public_path($blog->featured_image);

                    if (file_exists($existing_image)) {
                        unlink($existing_image);
                    }
                }
                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $data['featured_image'] = "blogs/blog_image/" . $picture;
            }


            // Thumb Image
            if ($request->hasFile('thumb_image')) {
                $file = $request->file('thumb_image');
                $extension = $file->getClientOriginalExtension();
                $picture = time() . '_' . Str::random(20) . '.' . $extension;
                if ($blog->thumb_image) {
                    $existing_image = public_path($blog->thumb_image);
                    if (file_exists($existing_image)) {
                        unlink($existing_image);
                    }
                }
                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $data['thumb_image'] = "blogs/blog_image/" . $picture;
            }


            // OG Image
            if ($request->hasFile('og_image')) {

                $file = $request->file('og_image');
                $extension = $file->getClientOriginalExtension();
                $picture = time() . '_' . Str::random(20) . '.' . $extension;
                if ($blog->og_image) {

                    $existing_image = public_path($blog->og_image);

                    if (file_exists($existing_image)) {
                        unlink($existing_image);
                    }
                }
                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $data['og_image'] = "blogs/blog_image/" . $picture;
            }

            $blog->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Blog Updated Successfully',
                'data' => $blog
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteblog($id)
    {
        try {
            $blog = Blog::find($id);
            $blog->update([
                'deleted_by' => 1
            ]);
            $blog->delete();

            return $this->successResponse("Blog Deleted", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeblogstatus(Request $request, $id)
    {
        try {
            $blog = Blog::find($id);

            if (!$blog) {
                return $this->errorResponse("Blog not found", 404);
            }

            $blog->active_status = $blog->active_status == 1 ? 0 : 1;

            $blog->save();

            return $this->successResponse("Blog Status Updated", Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Author
    public function getAllAuthors()
    {
        try {
            $data = Author::where('status', 1)->get();
            return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Tags
    // ----------------------------------------------------------------------------------
    public function addtag(Request $request)
    {
        try {

            $data = [
                "tag_name" => $request->tag_name,
                "slug" => $request->slug,
                "created_by" => 1,
                "updated_by" => 1,
            ];

            Tag::create($data);

            return $this->successResponse("Tag Created Successfully", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function alltags(Request $request)
    {
        $query = Tag::query();

        if ($request->searchBy != '' && $request->searchBy != null) {
            $query->where('tag_name', 'LIKE', '%' . $request->searchBy . '%')
                ->orWhere('slug', 'LIKE', '%' . $request->searchBy . '%');
        }
        if ($request->status !== '' && $request->status !== null) {

            $query->where('active_status', $request->status);
        }

        $per_page = $request->per_page ?? 10;
        $data = $query->orderBy('id', 'DESC')
            ->paginate($per_page);
        return response()->json([
            'status' => 1,
            'message' => Config::get('constants.RECORD_FETCHED'),
            'data' => $data->items(),
            'total' => $data->total()
        ]);
    }

    public function getTag(Request $request)
    {
        $data = Tag::where('active_status', 1)->get();

        return response()->json([
            'status' => 1,
            'message' => Config::get('constants.RECORD_FETCHED'),
            'data' => $data
        ]);
    }


    public function updatetag(Request $request, $id)
    {
        try {

            $data = [
                "tag_name" => $request->tag_name,
                "slug" => $request->slug,
                "created_by" => 1,
                "updated_by" => 1,
            ];

            Tag::where('id', $id)->update($data);
            return $this->successResponse("Blog Updated", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function changetagstatus(Request $request, $id)
    {
        try {
            $tag = Tag::find($id);

            if (!$tag) {
                return $this->errorResponse("Tag not found", 404);
            }

            $tag->active_status = $tag->active_status == 1 ? 0 : 1;

            $tag->save();

            return $this->successResponse("Tag Status Updated", Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletetag($id)
    {
        try {
            $tag = Tag::find($id);
            $tag->update([
                'deleted_by' => 1
            ]);
            $tag->delete();

            return $this->successResponse("Tag Deleted", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Tags Map
    // ----------------------------------------------------------------------------------

    public function addtagmap(Request $request)
    {
        try {
            $insertData = [];
            foreach ($request->tag_id as $tagId) {
                $insertData[] = [
                    "blog_id" => $request->blog_id,
                    "tag_id" => $tagId,
                    "created_by" => 1,
                    "updated_by" => 1,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            }

            Tagmap::insert($insertData);

            return $this->successResponse("Tagmap Created Successfully", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }


    public function alltagmaps(Request $request)
    {
        $query = Tagmap::join('blogs', 'blogs.id', '=', 'blog_tag_map.blog_id')
            ->join('blog_tags', 'blog_tags.id', '=', 'blog_tag_map.tag_id')
            ->select(
                'blog_tag_map.blog_id',
                'blog_tag_map.created_by',
                'blogs.title',
                DB::raw("GROUP_CONCAT(blog_tags.tag_name SEPARATOR ', ') as tags"),
                DB::raw("GROUP_CONCAT(blog_tags.id) as tag_ids")
            )
            ->groupBy(
                'blog_tag_map.blog_id',
                'blogs.title',
                'blog_tag_map.created_by'
            );

        // Search Filter
        if ($request->searchBy != '' && $request->searchBy != null) {

            $query->where(function ($q) use ($request) {

                $q->where('blogs.title', 'LIKE', '%' . $request->searchBy . '%')
                    ->orWhere('blog_tags.tag_name', 'LIKE', '%' . $request->searchBy . '%');
            });
        }

        // Per Page
        $per_page = $request->per_page ?? 10;

        $data = $query->orderBy('blog_tag_map.blog_id', 'DESC')
            ->paginate($per_page);

        return response()->json([
            'status' => 1,
            'message' => 'Record Fetched Successfully',
            'data' => $data->items(),
            'total' => $data->total()
        ]);
    }


    public function updatetagmap(Request $request, $id)
    {
        try {

            // Delete old tags of this blog
            Tagmap::where('blog_id', $request->blog_id)->delete();

            $insertData = [];
            foreach ($request->tag_id as $tagId) {
                $insertData[] = [
                    "blog_id"   => $request->blog_id,
                    "tag_id"    => $tagId,
                    "created_by" => 1,
                    "updated_by" => 1,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            }

            // Insert new selected tags
            Tagmap::insert($insertData);

            return $this->successResponse(
                "Tag map Updated Successfully",
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_PARTIAL_CONTENT
            );
        }
    }

    // public function deletetagmap($id)
    // {
    //     try {
    //         $tag = Tagmap::find($id);
    //         $tag->update([
    //             'deleted_by' => 1
    //         ]);
    //         $tag->delete();

    //         return $this->successResponse("Tag Map Deleted", Response::HTTP_OK);
    //     } catch (Exception $e) {
    //         return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    public function deletetagmap($blogId)
    {
        try {

            Tagmap::where('blog_id', $blogId)->delete();

            return $this->successResponse(
                "Tag Map Deleted Successfully",
                Response::HTTP_OK
            );
        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    //Blog route
    // ----------------------------------------------------------------------------------
    public function addblogroute(Request $request)
    {
        try {
            $data = [
                "blog_id" => $request->blog_id,
                "from_city_id" => $request->from_city_id,
                "to_city_id" => $request->to_city_id,
                "route_slug" => $request->route_slug,
                "created_by" => 1,
                "updated_by" => 1,
            ];

            Blogroute::create($data);
            return $this->successResponse("Blogroute Created Successfully", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function allblogroute()
    {
        $data = Blogroute::all();
        return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }


    public function updateblogroute(Request $request, $id)
    {
        try {
            $data = [
                "blog_id" => $request->blog_id,
                "from_city_id" => $request->from_city_id,
                "to_city_id" => $request->to_city_id,
                "route_slug" => $request->route_slug,
                "created_by" => 1,
                "updated_by" => 1,
            ];

            Blogroute::where('id', $id)->update($data);
            return $this->successResponse("Blog route Updated", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function deleteblogroute($id)
    {
        try {
            $tag = Blogroute::find($id);
            $tag->update([
                'deleted_by' => 1
            ]);
            $tag->delete();

            return $this->successResponse("Blog route Deleted", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
