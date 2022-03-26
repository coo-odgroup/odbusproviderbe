<?php

namespace App\Repositories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
class ReviewRepository
{
    /**
     * @var Review
     */
    protected $review;
    protected $user;

    /**
     * ReviewRepository constructor.
     *
     * @param Review $review
     */
    public function __construct(Review $review, User $user)
    {
        $this->review = $review;
        $this->user = $user;
    }

     public function getAll()
    {
        $data = $this->review->where('status', 1)->orderBy('id',"DESC")->get() ;
       
        return $data;     
    }


    public function getData($request)
    {
        // Log::info($request);

        $operator_id = $request->bus_operator_id ;
        $paginate = $request->rows_number; 
        $start_date  =  $request->rangeFromDate;
        $end_date  =  $request->rangeToDate; 
        $user_id = $request['user_id'] ;
        $role_id = $request['role_id'] ;

        
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        $data= $this->review->with('bus.busOperator')->where('status','!=' ,2)
                            ->orderBy('id',"DESC");
        if (!empty($start_date) && !empty($end_date)) {
            if($start_date == $end_date)
            {
                $data =$data->where('created_at','like','%'.$start_date.'%');     
            }
            else
            {
                $data = $data->whereBetween('created_at', [$start_date, $end_date]);   
            }
        }
        if($operator_id!= null)
        {
          $data = $data->Where('bus_operator_id', $operator_id);
        }
        if($user_id!= null && $role_id!= 1 )
          {
            $data = $data->Where('user_id', $user_id);
          }


        $data=$data->paginate($paginate); 
        return $data;

    } 

    public function deleteData($id)
    {
        $review = $this->review->find($id);
        $review->status = 2;
        $review->update();

        return $review;      

    }

    public function changeStatus($id)
    {
        $post = $this->review->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
    

}