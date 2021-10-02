<?php

namespace App\Services;


use App\Repositories\PageContentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class PageContentService
{
    protected $pagecontentRepository;

    
   
    public function __construct(PageContentRepository $pagecontentRepository)
    {
        $this->pagecontentRepository = $pagecontentRepository;
    }

   
    // public function deleteById($id)
    // {
    //     try {
    //         $pagecontent = $this->pagecontentRepository->delete($id);
    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $pagecontent;
    // }
    
    public function getAll()
    {      
        return $this->pagecontentRepository->getAll();
    }

    public function addpagecontent($request)
    {
        return $this->pagecontentRepository->addpagecontent($request);
    } 
    public function updatepagecontent($request,$id)
    {
        return $this->pagecontentRepository->updatepagecontent($request,$id);
    }
    public function deletepagecontent($id)
    {
        return $this->pagecontentRepository->deletepagecontent($id);
    } 



   

}