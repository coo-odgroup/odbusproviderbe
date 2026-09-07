<?php

return [
    'MSG91_AUTH_KEY' => env('MSG91_AUTH_KEY','450989AZnXXzHCbpaB6979bcd0P1'),
    'MSG91_SENDER_ID' => env('MSG91_SENDER_ID','ODBUUS'),
    'templates' => [
        'booking' => env('MSG91_BOOKING_TEMPLATE'),
        'Forgot_or_Reset_OTP' => '6a6db394de05af8ba10f4dc2',
        'Agent_Regi_OTP' => '69ba64144ddfd6cf020a50b2',
        'Welcome_Login_credentials' => '6a9bc01775d4ea792a06ac74',
        'documents_received' => '6a9bbf33e44abf4ea904acc2',
        'Documents_Verification_unsuccessful' => '6a9bc2f186d141ec1603b614',
    ],

    'campaign_base_url' => 'https://control.msg91.com/api/v5/campaign/api/campaigns/',
    'template_image_url' => 'https://provider.odbus.co.in/uploads/logo/ODBUS_YELLOW_BG_LOGOWHATSAPP-1.jpg',
    'pdf_url' => 'https://www.odbus.in/pnr/',

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