<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

use App\Services\ReviewService;
use Exception;
use InvalidArgumentException;

use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
     /**
     * @var reviewService
     */
    protected $reviewService;

    /**
     * PostController Constructor
     *
     * @param ReviewService $reviewService
     *
     */
    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }



    public function getAllReview() {
        $prod = $this->reviewService->getAll();;
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$prod;
        return response($output, 200);
    }

    public function createReview(Request $request) {
        $data = $request->only([
            'pnr','bus_id','customer_id','reference_key','rating_overall','rating_comfort','rating_clean','rating_behavior',
            'rating_timing','comments',
          
        ]);
        $reviewRules = [
          'pnr' => 'required',
          'bus_id' => 'required',
          'customer_id' => 'required',
          'reference_key' => 'required',
          'rating_overall' => 'required',
          'rating_comfort' => 'required',
          'rating_clean' => 'required',
          'rating_behavior' => 'required',
          'rating_timing' => 'required',
          'comments' => 'required',
        ];
      
        $reviewValidation = Validator::make($data, $reviewRules);
        
        if ($reviewValidation->fails()) {
          $errors = $reviewValidation->errors();
          return $errors->toJson();
        }
        
        $this->reviewService->savePostData($data);
    
        $output ['status']=1;
        $output ['message']='Data Added Successfully';
        return response($output, 200);
	
    } 

    public function updateReview(Request $request, $id) {
        $data = $request->only([
            'pnr','bus_id','customer_id','reference_key','rating_overall','rating_comfort','rating_clean','rating_behavior',
            'rating_timing','comments',
          
        ]);
        $reviewRules = [
          'pnr' => 'required',
          'bus_id' => 'required',
          'customer_id' => 'required',
          'reference_key' => 'required',
          'rating_overall' => 'required',
          'rating_comfort' => 'required',
          'rating_clean' => 'required',
          'rating_behavior' => 'required',
          'rating_timing' => 'required',
          'comments' => 'required',
        ];
      
      
        $reviewValidation = Validator::make($data, $reviewRules);
        
        if ($reviewValidation->fails()) {
          $errors = $reviewValidation->errors();
          return $errors->toJson();
        }
     
        $this->reviewService->updatePost($data, $id);
        $output ['status']=1;
        $output ['message']='Data updated successfully';
        return response($output, 200);
        
    }

    public function deleteReview ($id) {
      $this->reviewService->deleteById($id);
      $output ['status']=1;
      $output ['message']='Data Deleted successfully';
      return response($output, 200);
    }

    public function getReview($id) {
      $ame= $this->reviewService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$ame;
      return response($output, 200);

    }
    public function getReviewByBid($bid) {
        $ame= $this->reviewService->getreviewBid($bid);
        $output ['status']=1;
        $output ['message']='Single Data Fetched Successfully..';
        $output ['result']=$ame;
        return response($output, 200);
    }
    


}
