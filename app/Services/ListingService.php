<?php

namespace App\Services;

use App\Models\Coupon;
use App\Repositories\ListingRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ListingService
{
    
    protected $listingRepository;    
    public function __construct(ListingRepository $listingRepository)
    {
        $this->listingRepository = $listingRepository;
    }
    // public function getAll()
    // {
    //     return $this->listingRepository->getAll();
    // }

    
   
}