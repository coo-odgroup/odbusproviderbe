<?php

namespace App\Repositories;
use App\Models\Offers;
use Storage;


class OffersRepository
{
    /**
     * @var offers
     */
    protected $Offers;


    /**
     * OffersRepository constructor.
     *
     * @param offers $offers
     */
    public function __construct(Offers $offers)
    {
        $this->offers = $offers;
    }

    /**
     * Get all offersRepository.
     *
     * @return offersRepository $offersRepository
     */
    public function getAll()
    {
        return $this->offers->whereNotIn('status', [2])->get();
    }

    /**
     * Get offersRepository by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->offers
            ->where('id', $id)
            ->get();
    }
    public function getByBusId($bid)
    {
        return $this->offers->whereNotIn('status', [2])
            ->where('bus_id', $bid)
            ->get();
    }

    /**
     * Save offersRepository
     *
     * @param $data
     * @return offersRepository
     */
    public function getModel($data, offersRepository $offersRepository)
    {
        $offersRepository->offer_category_id = $data['offer_category_id'];
        $offersRepository->offer_image = $data['offer_image'];
        $offersRepository->offer_text = $data['offer_text'];
        $offersRepository->created_by = $data['created_by'];
        return $offersRepository;
    }

    public function save($data)
    {

        $offersRepository = new $this->offers;
        $offersRepository=$this->getModel($data,$offersRepository);
        $offersRepository->save();
        return $offersRepository;
    }

    /**
     * Update offersRepository
     *
     * @param $data
     * @return offersRepository
     */
    public function update($data, $id)
    {
        
        // $post = $this->offers->find($id);

        // $post->mobileno = $data['mobileno'];

        // $post->update();

        // return $post;
    }

    /**
     * Update offersRepository
     *
     * @param $data
     * @return offersRepository
     */
    public function delete($id)
    {
        
        $post = $this->offers->find($id);
        $post->status = 2;
        $post->update();
        return $post;
    }


    public function getDatatable($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        if(!is_numeric($rowperpage))
        {
            $rowperpage=Config::get('constants.ALL_RECORDS');
        }

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        $totalRecords = $this->offers
        ->whereNotIn('status', [2])
        ->count();
        $totalRecordswithFilter = $this->offers
        ->where('offer_text', 'like', "%" .$searchValue . "%")
        ->whereNotIn('status', [2])
        ->count();
        
        $records = $this->offers->orderBy($columnName,$columnSortOrder)
            ->where('offer_text', "like", "%" .$searchValue . "%")
            ->whereNotIn('status', [2])
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach($records as $key=>$record)
        {
            $data_arr[]=$record->toArray();
            $data_arr[$key]['created_at']=date('j M Y h:i a',strtotime($record->created_at));
            $data_arr[$key]['updated_at']=date('j M Y h:i a',strtotime($record->updated_at));
        }    
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ); 
        return ($response);
    }

}