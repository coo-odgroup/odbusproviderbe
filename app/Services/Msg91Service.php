<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Msg91Service
{
    public function customer_ticket_booking($data)
    {
        $formattedDate = Carbon::parse($data['doj'])->format('d-M-Y');
        $departureTime = Carbon::parse($data['dep'])->format('H:i');

        $variables = [
            "header_1" => [
                "type" => "image",
                "value" => config('msg91.template_image_url')
            ],

            "body_var_1" => ["type" => "text", "value" => $data['customer_name']],
            "body_var_2" => ["type" => "text", "value" => $data['pnr']],
            "body_var_3" => ["type" => "text", "value" => $data['source']],
            "body_var_4" => ["type" => "text", "value" => $data['boarding_point']],
            "body_var_5" => ["type" => "text", "value" => $data['destination']],
            "body_var_6" => ["type" => "text", "value" => $data['dropping_point']],
            "body_var_7" => ["type" => "text", "value" => $data['busname']],
            "body_var_8" => ["type" => "text", "value" => $data['vechicle_no']],
            "body_var_9" => ["type" => "text", "value" => $formattedDate],
            "body_var_10" => ["type" => "text", "value" => $departureTime],
            "body_var_11" => ["type" => "text", "value" => $data['passanger']],
            "body_var_12" => ["type" => "text", "value" => $data['seat']],
            "body_var_13" => ["type" => "text", "value" => $data['conductor_no']],

            "button_1" => ["type" => "text", "value" => "https://play.google.com/store/apps/details?id=com.od.odbus&pli=1"],
            "button_2" => ["type" => "text", "value" => $data['pnr']],


            "var1" => ["type" => "text", "value" => $data['customer_name']],
            "var2" => ["type" => "text", "value" => $data['pnr']],
            "var3" => ["type" => "text", "value" => $data['source']],
            "var4" => ["type" => "text", "value" => $data['destination']],
            "var5" => ["type" => "text", "value" => $data['busname']],
            "var6" => ["type" => "text", "value" => $data['vechicle_no']],
            "var7" => ["type" => "text", "value" => $formattedDate],
            "var8" => ["type" => "text", "value" => $departureTime],
            "var9" => ["type" => "text", "value" => $data['passanger']],
            "var10" => ["type" => "text", "value" => $data['seat']],
            "var11" => ["type" => "text", "value" => $data['fare']],
            "var12" => ["type" => "text", "value" => $data['conductor_no']],

        ];

        $campaignName = config('msg91.campaigns.customer_ticket_booking');
        $url = config('msg91.campaign_base_url') . $campaignName . '/run';

        // $url = "https://control.msg91.com/api/v5/campaign/api/campaigns/customer-ticket-booking/run";

        $to[] = [
            "mobiles" => "91" . $data['customer_mob'],
            "variables" => $variables
        ];

        $postData = [
            "data" => [
                "sendTo" => [
                    [
                        "to" => $to,
                        "variables" => $variables
                    ]
                ]
            ]
        ];

        // return $postData;


        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                'authkey: ' . config('msg91.MSG91_AUTH_KEY'),
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return curl_error($curl);
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    public function cmo_ticket_booking($data)
    {
        $toNumbers = [];
        if (!empty($data['conductor_no'])) {
            $conductorNumbers = explode(',', $data['conductor_no']);

            foreach ($conductorNumbers as $num) {
                $num = trim($num);
                if (!empty($num)) {
                    $toNumbers[] = $num;
                }
            }
        }

        $formattedDate = Carbon::parse($data['doj'])->format('d-M-Y');
        $departureTime = Carbon::parse($data['dep'])->format('H:i');

        $variables = [

            "header_1" => [
                "type" => "image",
                "value" => config('msg91.template_image_url')
            ],

            "body_var_1" => ["type" => "text", "value" => $data['pnr']],
            "body_var_2" => ["type" => "text", "value" => $data['busname']],
            "body_var_3" => ["type" => "text", "value" => $data['vechicle_no']],
            "body_var_4" => ["type" => "text", "value" => $formattedDate],
            "body_var_5" => ["type" => "text", "value" => $departureTime],
            "body_var_6" => ["type" => "text", "value" => $data['source']],
            "body_var_7" => ["type" => "text", "value" => $data['boarding_point']],
            "body_var_8" => ["type" => "text", "value" => $data['destination']],
            "body_var_9" => ["type" => "text", "value" => $data['dropping_point']],
            "body_var_10" => ["type" => "text", "value" => $data['passanger']],
            "body_var_11" => ["type" => "text", "value" => $data['seat']],
            "body_var_12" => ["type" => "text", "value" => $data['customer_mob']],


            "var1" => ["type" => "text", "value" => $data['pnr']],
            "var2" => ["type" => "text", "value" => $data['busname']],
            "var3" => ["type" => "text", "value" => $data['vechicle_no']],
            "var4" => ["type" => "text", "value" => $formattedDate],
            "var5" => ["type" => "text", "value" => $departureTime],
            "var6" => ["type" => "text", "value" => $data['source']],
            "var7" => ["type" => "text", "value" => $data['destination']],
            "var8" => ["type" => "text", "value" => $data['passanger']],
            "var9" => ["type" => "text", "value" => $data['seat']],
            "var10" => ["type" => "text", "value" => $data['customer_mob']],

        ];

        $to = [];

        $campaignName = config('msg91.campaigns.cmo_ticket_booking');
        $url = config('msg91.campaign_base_url') . $campaignName . '/run';

        // $url = "https://control.msg91.com/api/v5/campaign/api/campaigns/cmo-ticket-booking-flow/run";

        foreach ($toNumbers as $number) {
            if (!empty($number)) {
                $to[] = [
                    "mobiles" => "91" . trim($number),
                    "variables" => $variables
                ];
            }
        }

        $postData = [
            "data" => [
                "sendTo" => [
                    [
                        "to" => $to,
                        "variables" => $variables
                    ]
                ]
            ]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                'authkey: ' . config('msg91.MSG91_AUTH_KEY'),
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return curl_error($curl);
        }

        curl_close($curl);

        return json_decode($response, true);
    }


    //cancel ticket sms send to customer
    public function sendSmsTicketCancel($data)
    {
        $journeydate = Carbon::parse($data['doj'])->format('d-M-Y');
        $variables = [

            "header_1" => [
                "type" => "image",
                "value" => config('msg91.template_image_url')
            ],

            "body_var_1" => ["type" => "text", "value" => $data['customer_name']],
            "body_var_2" => ["type" => "text", "value" => $data['pnr']],
            "body_var_3" => ["type" => "text", "value" => $data['busname']],
            "body_var_4" => ["type" => "text", "value" => $data['vechicle_no']],
            "body_var_5" => ["type" => "text", "value" => $data['source']],
            "body_var_6" => ["type" => "text", "value" => $data['destination']],
            "body_var_7" => ["type" => "text", "value" => $journeydate],
            "body_var_8" => ["type" => "text", "value" => $data['seat']],
            "body_var_9" => ["type" => "text", "value" => $data['refundAmount']],

            "button_1" => ["type" => "text", "value" => "https://play.google.com/store/apps/details?id=com.od.odbus&pli=1"],


            "var1" => ["type" => "text", "value" => $data['customer_name']],
            "var2" => ["type" => "text", "value" => $data['pnr']],
            "var3" => ["type" => "text", "value" => $data['busname']],
            "var4" => ["type" => "text", "value" => $data['vechicle_no']],
            "var5" => ["type" => "text", "value" => $data['source']],
            "var6" => ["type" => "text", "value" => $data['destination']],
            "var7" => ["type" => "text", "value" => $journeydate],
            "var8" => ["type" => "text", "value" => $data['seat']],
            "var9" => ["type" => "text", "value" => $data['refundAmount']],

        ];


        $campaignName = config('msg91.campaigns.customer_ticket_cancellation');
        $url = config('msg91.campaign_base_url') . $campaignName . '/run';
        // $url = "https://control.msg91.com/api/v5/campaign/api/campaigns/customer-ticket-cancellation-flow/run";

        $to[] = [
            "mobiles" => "91" . $data['customer_mob'],
            "variables" => $variables
        ];

        $postData = [
            "data" => [
                "sendTo" => [
                    [
                        "to" => $to,
                        "variables" => $variables
                    ]
                ]
            ]
        ];

        // return $postData;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                'authkey: ' . config('msg91.MSG91_AUTH_KEY'),
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return curl_error($curl);
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    //cancel ticket sms send to cmo
    public function cmo_ticket_cancel($data)
    {
        // return $data;
        $getNumber = explode(',', $data['cmo_no']);

        $journeydate = Carbon::parse($data['doj'])->format('d-M-Y');
        $variables = [

            "header_1" => [
                "type" => "image",
                "value" => config('msg91.template_image_url')
            ],

            "body_var_1" => ["type" => "text", "value" => $data['pnr']],
            "body_var_2" => ["type" => "text", "value" => $data['busname']],
            "body_var_3" => ["type" => "text", "value" => $data['vechicle_no']],
            "body_var_4" => ["type" => "text", "value" => $data['source']],
            "body_var_5" => ["type" => "text", "value" => $data['destination']],
            "body_var_6" => ["type" => "text", "value" => $journeydate],
            "body_var_7" => ["type" => "text", "value" => $data['seat']],


            "var1" => ["type" => "text", "value" => $data['pnr']],
            "var2" => ["type" => "text", "value" => $data['busname']],
            "var3" => ["type" => "text", "value" => $data['vechicle_no']],
            "var4" => ["type" => "text", "value" => $data['source']],
            "var5" => ["type" => "text", "value" => $data['destination']],
            "var6" => ["type" => "text", "value" => $journeydate],
            "var7" => ["type" => "text", "value" => $data['seat']],

        ];

        // return $variables;

        $to = [];

        $campaignName = config('msg91.campaigns.cmo_ticket_cancellation');
        $url = config('msg91.campaign_base_url') . $campaignName . '/run';
        // $url = "https://control.msg91.com/api/v5/campaign/api/campaigns/cmo-ticket-cancellation/run";

        foreach ($getNumber as $number) {
            if (!empty($number)) {
                $to[] = [
                    "mobiles" => "91" . trim($number),
                    "variables" => $variables
                ];
            }
        }

        $postData = [
            "data" => [
                "sendTo" => [
                    [
                        "to" => $to,
                        "variables" => $variables
                    ]
                ]
            ]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                'authkey: ' . config('msg91.MSG91_AUTH_KEY'),
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return curl_error($curl);
        }

        curl_close($curl);

        return json_decode($response, true);
    }


    //forgot otp send
    public function forgot_otp($data)
    {
        $smsData = [
            "var1" => $data['otp'],
        ];

        $postData = array_merge([
            "flow_id" => config('msg91.templates.Forgot_or_Reset_OTP'),
            "mobiles" => "91" . $data['mobile_no'],
        ], $smsData);

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
}
