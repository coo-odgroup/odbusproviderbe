<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    // This is is used in common
    // ---------------------------------------------
    private $baseUrl;
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->baseUrl = 'https://sandbox.cashfree.com/';
        $this->clientId = 'CF10857740D9Q7P56O7G0C738P35F0';
        $this->clientSecret = 'cfsk_ma_test_c24e74d374dd320991e5af53e8e1b98d_9349b347';
    }
    public function commonCurl($method, $endpoint, $payload = [])
    {
        // return [$method, $endpoint, $payload];
        $curl = curl_init();

        $options = [
            CURLOPT_URL => $this->baseUrl . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-client-id: ' . $this->clientId,
                'x-client-secret: ' . $this->clientSecret,
            ],
        ];

        if (!empty($payload)) {
            $options[CURLOPT_POSTFIELDS] = json_encode($payload);
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return [
            'success' => empty($error),
            'http_code' => $httpCode,
            'response' => json_decode($response, true),
            'error' => $error
        ];
    }

    // ------------------------------------------------------------------------------



    public function verifyPan(Request $request)
    {
        // return $request->all();
        $response = $this->commonCurl(
            'POST',
            'verification/pan',
            [
                'pan' => strtoupper($request->pan),
            ]
        );

        return response()->json($response);
    }

    public function panStatus(Request $request)
    {
        // return $request->all();
        $response = $this->commonCurl(
            'GET',
            'verification/pan/' . strtoupper($request->pan)
        );

        return response()->json($response);
    }


    //Aadhaar Masking

    public function maskAadhaar(Request $request)
    {
        $request->validate([
            'aadhaar' => 'required|file|mimes:jpg,jpeg,png|max:10240',
        ]);

        $file = $request->file('aadhaar');

        $response = $this->aadhaarMaskingCurl(
            $file->getRealPath(),
            $file->getClientOriginalName(),
            $file->getMimeType(),
            'aadhaar_' . time()
        );

        return response()->json($response);
    }


    public function aadhaarMaskingCurl($imagePath, $originalName, $mimeType, $verificationId)
    {
        $curl = curl_init();

        $extension = strtolower(
            pathinfo($originalName, PATHINFO_EXTENSION)
        );

        // Validate extension
        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return [
                'success' => false,
                'http_code' => 400,
                'response' => [
                    'message' => 'Invalid file format'
                ],
                'error' => ''
            ];
        }

        // Force correct MIME type
        if (in_array($extension, ['jpg', 'jpeg'])) {
            $mimeType = 'image/jpeg';
        } elseif ($extension === 'png') {
            $mimeType = 'image/png';
        }

        $curlFile = new \CURLFile(
            $imagePath,
            $mimeType,
            $originalName
        );

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl . 'verification/aadhaar-masking',

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_CUSTOMREQUEST => 'POST',

            CURLOPT_HTTPHEADER => [
                'x-client-id: ' . $this->clientId,
                'x-client-secret: ' . $this->clientSecret,
            ],

            CURLOPT_POSTFIELDS => [
                'image' => $curlFile,
                'verification_id' => $verificationId,
            ],
        ]);

        $response = curl_exec($curl);

        $error = curl_error($curl);

        $httpCode = curl_getinfo(
            $curl,
            CURLINFO_HTTP_CODE
        );

        curl_close($curl);

        return [
            'success' => empty($error),
            'http_code' => $httpCode,
            'response' => json_decode($response, true),
            'error' => $error
        ];
    }
}
