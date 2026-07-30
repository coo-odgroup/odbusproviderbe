<?php
//file : app/config/constants.php

return [
    'RECORD_FETCHED' => 'Record Fetched Successfully',
    'RECORD_REMOVED' => 'Record Removed Successfully',
    'RECORD_ADDED' => 'Record Added Successfully',
    'RECORD_UPDATED' => 'Record Updated Successfully',
    'RECORD_NOT_FOUND' => 'Record Not Found',
    'INVALID_ARGUMENT_PASSED' => 'Invalid Argument Passed',
    'UNABLE_CHANGE_STATUS' => 'Unable to Change Status',
    'ALL_RECORDS' => 10000,
    'OTP_GEN' => 'OTP generated',
    'OTP_NULL' => 'No Value provided in OTP',
    'OTP_INVALID' => 'Invalid OTP',
    'RESET_PASSWORD_SUCCESS' => 'Reset Password is successful',
    'OTP_VERIFIED' => 'OTP verification successful',
    'UNREGISTERED' => 'User is not registered',
    'ROLE_MISMATCH' => 'Role Mismatches',
    'OTP_NOT_VERIFIED' => 'OTP is not yet validated',
    'PWD_MISMATCH' => 'Incorrect Password',
    'INVALID_EMAIL' => 'Email ID does not exist',
    'INACTIVE_USER' => 'User Not Active. ',
    'LOGIN_SUCCESSFUL' => 'Login successful',
    'VERIFIED' => 'otp verification is successful',
    'REGT_SUCCESS' => 'Registration Successful',
    // 'CONSUMER_API_URL' => 'https://testing.odbus.co.in/api/',
    'CONSUMER_API_URL' => 'http://localhost:7001/ODBUS/odbusconsumerbesmsIntegration/api/',
    // 'CONSUMER_API_URL' => 'https://dolphinapi.odbus.co.in/api/',
    //'CONSUMER_API_URL' => 'http://127.0.0.1:8000/api/',
    'EXCEPTION_ERROR' => 'Error Occured',
    'CONSUMER_FRONT_URL' => 'https://www.odbus.in/',
    'BASE_URL' => 'https://odtestingssr.odbus.co.in/',
    'PUBLIC_PATH_URL' => 'https://consumer.odbus.co.in/',


    #PhonePe
    'MID' => env('MID', 'ODBUSUAT'),
    'CLIENT_ID' => env('CLIENT_ID', 'ODBUSUAT_251114164525072'),
    'CLIENT_VERSION' => env('CLIENT_VERSION', 1),
    'CLIENT_SECRET' => env('CLIENT_SECRET', 'NGYyMjVmYTAtMjU2My00NWIxLTg1MzItZjhjNjRjZDQwNDRk'),
    'GRANT_TYPE' => env('GRANT_TYPE', 'client_credentials'),
    'PHONPE_API_URL' => env('PHONPE_API_URL', 'https://api-preprod.phonepe.com/apis/pg-sandbox/'),
    'PHONPE_REDIRECT_URL' => env('PHONPE_REDIRECT_URL', 'https://odtesting.odbus.co.in/payment-status'),

    'REFUND_STATUS_CODE' => [
        'INITIATED' => 0,
        'PENDING'   => 1,
        'CONFIRMED' => 2,
        'COMPLETED' => 3,
        'FAILED'    => 4,
    ],

    //Test Environment
    'CASHFREE_KEY' => env('CASHFREE_KEY','TEST108577409ff7eb8e2b1cb161978f04775801'),
    'CASHFREE_SECRET'=> env('CASHFREE_SECRET','cfsk_ma_test_c0f4b0bd0ccd2731dfb130a93c1edc8b_2f49aced'),
    'CASHFREE_API_URL' => env('CASHFREE_API_URL','https://sandbox.cashfree.com/pg/orders'),

    //Live Environment
    // 'CASHFREE_KEY' => env('CASHFREE_KEY','1113683e1dcf93c8bec738e78f13863111'),
    // 'CASHFREE_SECRET'=> env('CASHFREE_SECRET','cfsk_ma_prod_1f6a44b064ab331b10e7484aae27f1fe_d8b8195b'),
    // 'CASHFREE_API_URL' => env('CASHFREE_API_URL','https://api.cashfree.com/pg/orders'),

];
