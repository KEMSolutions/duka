<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

    'kemapi' => [
        'user' => getenv('KEM_API_USER'),
        'secret' => getenv('KEM_API_KEY'),
    ],

    'mixpanel' => getenv('TRACKING_ID_MIXPANEL'),
    'ganalytics'=> getenv('TRACKING_ID_GOOGLEANALYTICS'),
];
