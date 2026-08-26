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

    public function generateAadhaarOtp(Request $request)
    {
        $request->validate([
            'aadhaar' => 'required|digits:12'
        ]);

        $response = $this->commonCurl(
            'POST',
            'verification/offline-aadhaar/otp',
            [
                'uid' => $request->aadhaar
            ]
        );

        return response()->json($response);
    }

    public function maskAadhaar(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png|max:10240',
        ]);

        try {

            $verificationId = 'aadhaar_' . Str::uuid();

            $response = Http::withHeaders([
                'x-client-id' => $this->clientId,
                'x-client-secret' => $this->clientSecret,
            ])
            ->attach(
                'image',
                fopen($request->file('image')->getRealPath(), 'r'),
                $request->file('image')->getClientOriginalName()
            )
            ->post(
                $this->baseUrl . '/verification/aadhaar-masking',
                [
                    'verification_id' => $verificationId
                ]
            );

            $data = $response->json();

            if ($response->successful() && isset($data['image_link'])) {

                return response()->json([
                    'status' => 1,
                    'message' => 'Aadhaar masked successfully',
                    'data' => $data
                ], 200);
            }

            return response()->json([
                'status' => 0,
                'message' => $data['message'] ?? 'Aadhaar masking failed',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
