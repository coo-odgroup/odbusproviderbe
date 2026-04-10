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
use Symfony\Component\HttpFoundation\Response;

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
                "created_by" => 1,
                "updated_by" => 1,
            ];

            Blogcategory::create($data);

            return $this->successResponse("Blog Category Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function allCategory()
    {
        $data = Blogcategory::all();
        return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
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


    //Blog
    // ----------------------------------------------------------------------------------
    public function addblog(Request $request)
    {
        try {

            $path = null;
            $thumpath = null;
            $ogpath = null;

            // Featured Image
            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                $filename = $file->getClientOriginalName();
                $picture = rand() . '-' . $filename;

                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $path = "blogs/blog_image/" . $picture;
            }

            // Thumb Image
            if ($request->hasFile('thumb_image')) {
                $file = $request->file('thumb_image');
                $filename = $file->getClientOriginalName();
                $picture = rand() . '-' . $filename;

                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $thumpath = "blogs/blog_image/" . $picture;
            }

            // OG Image
            if ($request->hasFile('og_image')) {
                $file = $request->file('og_image');
                $filename = $file->getClientOriginalName();
                $picture = rand() . '-' . $filename;

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

    public function allblog()
    {
        $data = Blog::join('blog_categories', 'blog_categories.id', '=', 'blogs.category_id')
            ->select('blogs.*', 'blog_categories.category_name')
            ->get();
        return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
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

                // ✅ Store JSON properly
                "breadcrumb_schema" => $request->breadcrumb_schema ? json_encode($request->breadcrumb_schema) : null,
                "faq_schema" => $request->faq_schema ? json_encode($request->faq_schema) : null,
                "service_schema" => $request->service_schema ? json_encode($request->service_schema) : null,

                "updated_by" => 1,
            ];

            // ✅ Featured Image
            if ($request->hasFile('featured_image')) {

                $file = $request->file('featured_image');
                $picture = time() . '_' . $file->getClientOriginalName();

                if ($blog->featured_image) {
                    $existing_image = public_path($blog->featured_image);
                    if (file_exists($existing_image)) {
                        unlink($existing_image);
                    }
                }

                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $data['featured_image'] = "blogs/blog_image/" . $picture;
            }

            // ✅ Thumb Image
            if ($request->hasFile('thumb_image')) {

                $file = $request->file('thumb_image');
                $picture = time() . '_' . $file->getClientOriginalName();

                if ($blog->thumb_image) {
                    $existing_image = public_path($blog->thumb_image);
                    if (file_exists($existing_image)) {
                        unlink($existing_image);
                    }
                }

                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $data['thumb_image'] = "blogs/blog_image/" . $picture;
            }

            // ✅ OG Image
            if ($request->hasFile('og_image')) {

                $file = $request->file('og_image');
                $picture = time() . '_' . $file->getClientOriginalName();

                if ($blog->og_image) {
                    $existing_image = public_path($blog->og_image);
                    if (file_exists($existing_image)) {
                        unlink($existing_image);
                    }
                }

                $file->move(public_path('uploads/blogs/blog_image'), $picture);
                $data['og_image'] = "blogs/blog_image/" . $picture;
            }

            // ✅ Update
            $blog->update($data);

            // ✅ Proper JSON response (VERY IMPORTANT)
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
    public function getAllAuthors(){
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

    public function alltags()
    {
        $data = Tag::all();
        return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
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

    public function alltagmaps()
    {
        $data = Tagmap::join('blogs', 'blogs.id', 'blog_tag_map.blog_id')
            ->join('blog_tags', 'blog_tags.id', 'blog_tag_map.tag_id')
            ->select('blogs.title', 'blog_tags.tag_name', 'blog_tag_map.*')
            ->get();
        return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }


    public function updatetagmap(Request $request, $id)
    {
        try {
            $data = [
                "blog_id" => $request->blog_id,
                "tag_id" => $request->tag_id,
                "created_by" => 1,
                "updated_by" => 1,
            ];

            Tagmap::where('id', $id)->update($data);
            return $this->successResponse("Tag map Updated", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function deletetagmap($id)
    {
        try {
            $tag = Tagmap::find($id);
            $tag->update([
                'deleted_by' => 1
            ]);
            $tag->delete();

            return $this->successResponse("Tag Map Deleted", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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
