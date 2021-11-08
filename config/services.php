<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],


    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'mailjet' => [
        'key' => env('MAILJET_APIKEY'),
        'secret' => env('MAILJET_APISECRET'),
    ],

    'email' => [
        'subject' => env('EMAIL_SUBJECT'),
        'subjectTicket' => env('EMAIL_TICKET_SUBJECT'),
        'subjectTicketCancel' => env('EMAIL_TICKET_CANCEL_SUBJECT')
    ],

    'sms' => [
        'otpservice' => env('OTP_SERVICE','textLocal'),
        'otp_service_enabled' => true,
        'textlocal' => [
            'key' => env('SMS_TEXTLOCAL_KEY'),
            'url_send' => env('TXTLOCAL_SEND_SMS_URL'),
            'url_status' => env('TXTLOCAL_STATUS_SMS_URL'),
            'message' => env('SMS_TEMPLATE'),
            'msgTicket' => env('SMS_TKT_TEMPLATE'),
            'cancelTicket' => env('CANCEL_TKT_TEMPLATE'),
            'senderid' => env('SENDER_ID'),
        ],
        'indiaHub' => [
            'key' => env('SMS_TEXTLOCAL_KEY'),
            'url' => env('TEXT_LOCAL_SMS_URL'),
            'url_msg' => env('TEXT_LOCAL_MESSAGE_URL'),
        ]
    ],
    

];
