<?php

return [
    'MSG91_AUTH_KEY' => env('MSG91_AUTH_KEY','450989AZnXXzHCbpaB6979bcd0P1'),
    'MSG91_SENDER_ID' => env('MSG91_SENDER_ID','ODBUUS'),
    'templates' => [
        'booking' => env('MSG91_BOOKING_TEMPLATE'),
    ],

    'campaign_base_url' => 'https://control.msg91.com/api/v5/campaign/api/campaigns/',
    'template_image_url' => 'https://provider.odbus.co.in/uploads/logo/ODBUS_YELLOW_BG_LOGOWHATSAPP-1.jpg',
    'pdf_url' => 'https://odtestingssr.odbus.co.in/pnr/',

    'campaigns' => [
        'otp' => 'sign-and-login-otp',
        'cmo_ticket_booking' => 'cmo-ticket-booking-flow',
        'customer_ticket_booking' => 'customer-ticket-booking',
        'customer_ticket_cancellation' => 'customer-ticket-cancellation-flow',
        'cmo_ticket_cancellation' => 'cmo-ticket-cancellation',
        'agent_ticket_booking' => 'agent-ticket-booking',
        'agent_ticket_cancellation' => 'agent-ticket-cancellation',
        'new_agent_registration' => 'new-agent-registration',
        'agent_pnr_cancellation_otp' => 'agent-pnr-cancellation-otp',
    ]
];