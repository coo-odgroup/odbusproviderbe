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
        $rangeFromDate  =  $request->rangeFromDate;
        $rangeToDate  =  $request->rangeToDate; 
        $user_id = $request['user_id'] ;
        $role_id = $request['role_id'] ;

        if(!empty($rangeFromDate))
        {
            if(strlen($rangeFromDate['month'])==1)
            {
                $rangeFromDate['month']="0".$rangeFromDate['month'];
            }
            if(strlen($rangeFromDate['day'])==1)
            {
                $rangeFromDate['day']="0".$rangeFromDate['day'];
            }

            $start_date = $rangeFromDate['year'].'-'.$rangeFromDate['month'].'-'.$rangeFromDate['day'] ;     
        }
         if(!empty($rangeToDate))
        {
            if(strlen($rangeToDate['month'])==1)
            {
                $rangeToDate['month']="0".$rangeToDate['month'];
            }
            if(strlen($rangeToDate['day'])==1)
            {
                $rangeToDate['day']="0".$rangeToDate['day'];
            }

            $end_date = $rangeToDate['year'].'-'.$rangeToDate['month'].'-'.$rangeToDate['day'] ;     
        }
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
            $data = $data->whereBetween('created_at', [$start_date, $end_date]);
            
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