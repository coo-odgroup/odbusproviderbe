<?php

return [
    'MSG91_AUTH_KEY' => env('MSG91_AUTH_KEY'),
    'MSG91_SENDER_ID' => env('MSG91_SENDER_ID'),
    'templates' => [
        'booking' => env('MSG91_BOOKING_TEMPLATE'),
    ],

    'campaigns' => [
        'otp' => 'sign-and-login-otp',
        'cmo_ticket_booking' => 'cmo-ticket-booking-flow',
        'customer_ticket_booking' => 'test',
        'customer_ticket_cancellation' => 'customer-ticket-cancellation-flow',
        'cmo_ticket_cancellation' => 'cmo-ticket-cancellation',
        'agent_ticket_booking' => 'agent-ticket-booking',
        'agent_ticket_cancellation' => 'agent-ticket-cancellation',
        'new_agent_registration' => 'new-agent-registration',
        'agent_pnr_cancellation_otp' => 'agent-pnr-cancellation-otp',
    ]
];