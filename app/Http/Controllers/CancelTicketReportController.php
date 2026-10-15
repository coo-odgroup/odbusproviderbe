<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\CancelTicketReportRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CancelTicketReportController extends Controller
{
    use ApiResponser;


    protected $cancelticketreportRepository;


    public function __construct(CancelTicketReportRepository $cancelticketreportRepository)
    {

        $this->cancelticketreportRepository = $cancelticketreportRepository;
    }
    public function getData(Request $request)
    {

        $cancelticketData = $this->cancelticketreportRepository->getData($request);
        return $this->successResponse($cancelticketData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function transactionDetail(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $orderId = $request->order_id;

        if (str_starts_with($orderId, 'ADJUST_')) {
            $orderId = preg_replace('/^ADJUST_\d+_/', '', $orderId);
        }

        $url = Config::get('constants.CASHFREE_API_URL');

        $clientId = Config::get('constants.CASHFREE_KEY');
        $clientSecret = Config::get('constants.CASHFREE_SECRET');

        $headers = [
            'x-api-version: 2023-08-01',
            'x-client-id: ' . $clientId,
            'x-client-secret: ' . $clientSecret,
            'Content-Type: application/json',
        ];

        $transactionUrl = $url . '/'
            . urlencode($orderId)
            . '/payments';

        $transactionCurl = curl_init();

        curl_setopt_array($transactionCurl, [
            CURLOPT_URL => $transactionUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $transactionResponse = curl_exec($transactionCurl);

        $transactionHttpCode = curl_getinfo(
            $transactionCurl,
            CURLINFO_HTTP_CODE
        );

        $transactionError = curl_error($transactionCurl);

        curl_close($transactionCurl);

        $refundUrl =  $url . '/'
            . urlencode($orderId)
            . '/refunds';

        $refundCurl = curl_init();

        curl_setopt_array($refundCurl, [
            CURLOPT_URL => $refundUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $refundResponse = curl_exec($refundCurl);

        $refundHttpCode = curl_getinfo(
            $refundCurl,
            CURLINFO_HTTP_CODE
        );

        $refundError = curl_error($refundCurl);

        curl_close($refundCurl);

        $transactionData = json_decode($transactionResponse, true);
        $refundData = json_decode($refundResponse, true);

        return response()->json([
            'status' => true,
            'order_id' => $orderId,

            'transaction_details' => $transactionData,

            'refund_details' => $refundData,

            'transaction_http_code' => $transactionHttpCode,
            'refund_http_code' => $refundHttpCode,

            'transaction_error' => $transactionError ?: null,
            'refund_error' => $refundError ?: null,
        ]);
    }
}
