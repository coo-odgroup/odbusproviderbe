<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Msg91Service
{
    public function sendBookingSms($mobile, $data)
    {
        $postData = array_merge([
            "flow_id" => config('msg91.templates.booking'),
            "mobiles" => "91" . $mobile,
        ], $data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.msg91.com/api/v5/flow/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => array(
                'authkey: ' . config('msg91.MSG91_AUTH_KEY'),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }


    public function sendWhatsappCampaign($numbers, $campaign, $variables)
    {
        return config('msg91.MSG91_AUTH_KEY');
        // return $campaign;
        // $url = "https://control.msg91.com/api/v5/campaign/api/campaigns/cmo-ticket-booking-flow/run";

        // $to = [];

        // foreach ($numbers as $num) {
        //     $to[] = [
        //         "mobiles" => "91" . $num,
        //         "variables" => $variables
        //     ];
        // }

        // $postData = [
        //     "data" => [
        //         "sendTo" => [
        //             [
        //                 "to" => $to,
        //                 "variables" => $variables
        //             ]
        //         ]
        //     ]
        // ];

        // $curl = curl_init();

        // curl_setopt_array($curl, [
        //     CURLOPT_URL => $url,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => json_encode($postData),
        //     CURLOPT_HTTPHEADER => [
        //         'authkey: ' . config('msg91.MSG91_AUTH_KEY'),
        //         'Content-Type: application/json'
        //     ],
        // ]);

        // $response = curl_exec($curl);

        // if (curl_errno($curl)) {
        //     return curl_error($curl);
        // }

        // curl_close($curl);

        // return json_decode($response, true);
    }
}
