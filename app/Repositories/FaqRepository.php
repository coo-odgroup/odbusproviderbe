<?php

namespace App\Repositories;

use App\Models\Faq;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/*Priyadarshi to Review*/

class FaqRepository
{
    protected $faq;


    public function __construct(Faq $faq)
    {
        $this->faq = $faq;
    }
    public function getAll()
    {

        return $this->faq->with('User')->where('status', 1)->get();
    }

    public function getAllData($request)
    {
        $paginate = $request['rows_number'];
        $title = $request['title'];
        $page_id = $request['page_id'];
        $faq_category_id = $request['faq_category_id'];

        $data = $this->faq
            ->leftJoin('page_content', 'faq.page_id', '=', 'page_content.id')
            ->leftJoin('faq_category', 'faq.faq_category_id', '=', 'faq_category.id')
            ->where('faq.status', '!=', 2)
            ->orderBy('faq.id', 'DESC')
            ->select(
                'faq.*',
                'page_content.page_name',
                'faq_category.category_name'
            );
        if ($paginate == 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif ($paginate == null) {
            $paginate = 10;
        }

        if ($title != null) {
            $data = $data->Where('title', $title);
        }

        if ($page_id != null) {
            $data = $data->Where('page_id', $page_id);
        }

        if ($faq_category_id != null) {
            $data = $data->Where('faq_category_id', $faq_category_id);
        }

        $data = $data->paginate($paginate);

        return array(
            "count" => $data->count(),
            "total" => $data->total(),
            "data" => $data
        );
    }

    public function getModel($data, faq $faq)
    {
        $faq->page_id = $data['page_id'];
        $faq->faq_category_id = $data['faq_category_id'];
        $faq->title = $data['title'];
        $faq->content = $data['content'];
        $faq->created_by = $data['created_by'];
        return $faq;
    }
    public function addfaq($data)
    {

        $faq = new $this->faq();
        $faq = $this->getModel($data, $faq);
        $faq->save();
        return $faq;
    }
    public function updatefaq($data, $id)
    {
        // Log::info($id);
        $faq = $this->faq->find($id);
        $faq = $this->getModel($data, $faq);
        $faq->update();
        return $faq;
    }


    public function deletefaq($id)
    {
        $faq = $this->faq->find($id);
        $faq->status = 2;
        $faq->update();

        return $faq;
    }

    public function changeStatus($id)
    {
        $faq = $this->faq->find($id);
        if ($faq->status == 0) {
            $faq->status = 1;
        } elseif ($faq->status == 1) {
            $faq->status = 0;
        }
        $faq->update();
        return $faq;
    }

    public function getAllfaqcategory()
    {
        return DB::table('faq_category')->where('status', 1)->get();
    }
}
