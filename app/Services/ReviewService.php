<?php

namespace App\Services;

use App\Models\Review;
use App\Repositories\ReviewRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ReviewService
{
    /**
     * @var $reviewRepository
     */
    protected $reviewRepository;

    /**
     * ReviewService constructor.
     *
     * @param ReviewRepository $reviewRepository
     */
    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function getAll()
    {
        return $this->reviewRepository->getAll();
    }
    
    public function getData($request)
    {
        return $this->reviewRepository->getData($request);
    }
    public function deleteData($id)
    {
        return $this->reviewRepository->deleteData($id);
    }

    public function changeStatus($id)
    {    
        return $this->reviewRepository->changeStatus($id);
    }


}