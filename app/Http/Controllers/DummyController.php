<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dummy;

// use App\Services\AppDownloadService;
// use Exception;


class DummyController extends Controller
{
    public function save(Dummy $dummy) {
        // return $dummy;
        print_r($dummy);
    }

}