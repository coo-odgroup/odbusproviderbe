<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SeoSettingService;
use Illuminate\Support\Facades\Validator;
use App\Repositories\SeoSettingRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\SeoSettingValidator;

class SeoSettingController extends Controller
{
    use ApiResponser;

    protected $seosettingService;
    protected $seosettingValidator;
    protected $seosettingRepository;

    


    public function __construct(SeoSettingService $seosettingService,SeoSettingRepository $seosettingRepository, SeoSettingValidator $seosettingValidator)
    {
        $this->seosettingService = $seosettingService;
        $this->seosettingValidator = $seosettingValidator;
        $this->seosettingRepository = $seosettingRepository;

    }

    public function getAllseosetting()
    {

        //$seosetting = $this->seosettingService->getAll();
        $seosetting = $this->seosettingRepository->getAll();
        return $this->successResponse($seosetting, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function seosettingData(Request $request)
    {

        //$seosetting = $this->seosettingService->seosettingData($request);
        $seosetting = $this->seosettingRepository->seosettingData($request);
        return $this->successResponse($seosetting, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function addseosetting(Request $request)
    {

        $data = $request->only([
        'page_url',
        'seo_type',
        'source_id',
        'destination_id',
        'user_id',
        'url_description',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'extra_meta',
        'canonical_url',
        'created_by'
        ]);

        $seosetting = $this->seosettingValidator->validate($data);


        if ($seosetting->fails()) {
            $errors = $seosetting->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            //$this->seosettingService->addseosetting($request);
            $this->seosettingRepository->addseosetting($request);
            return $this->successResponse(null, "SEO Setting Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            // Log::info($e);
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

    }
    public function updateseosetting(Request $request, $id)
    {

        $data = $request->only([
        'page_url',
        'user_id',
        'seo_type',
        'source_id',
        'destination_id',
        'url_description',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'extra_meta',
        'canonical_url',
        'created_by'
        ]);

        $seosetting = $this->seosettingValidator->validate($data);


        if ($seosetting->fails()) {
            $errors = $seosetting->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            //$this->seosettingService->updateseosetting($request, $id);
            $this->seosettingRepository->updateseosetting($request, $id);
            return $this->successResponse(null, "SEO Setting Updated", Response::HTTP_CREATED);
        } catch (Exception $e) {
            // Log::info($e);
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }

    }

    public function deleteseosetting($id)
    {
        //$seosetting = $this->seosettingService->deleteseosetting($id);
        $seosetting = $this->seosettingRepository->deleteseosetting($id);
        return $this->successResponse($seosetting, "SEO Setting Deleted", Response::HTTP_OK);

    }
    public function changeStatusseosetting($id)
    {
        //$seosetting = $this->seosettingService->changeStatusseosetting($id);
        $seosetting = $this->seosettingRepository->changeStatusseosetting($id);
        return $this->successResponse($seosetting, "SEO Setting Status Updated", Response::HTTP_OK);

    }





}
