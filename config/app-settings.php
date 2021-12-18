<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Settings type
     |--------------------------------------------------------------------------
     |
     | This is the list of the type of data allowed for the settings
     |
     */
    'type' => [
        'boolean',
        'string',
        // 'longstring',
        // 'array',
        // 'file',
        'int',
        'float',
        // 'date'
    ],

    /*
     |--------------------------------------------------------------------------
     | Current Settings
     |--------------------------------------------------------------------------
     |
     | This is the list of current settings for the website
     | Whenever the app is reloaded, the settings for the database
     | will be fetched here to populate the db.
     |
     */
    'settings' => [
        'cedisrate' => [
            'label' => 'Cedis Rate',
            'description' => 'The accepted conversion rate for 1 Cedis to Naira.',
            'type' => 'float',
            'inputlabel' => '&#8358;'
        ],
    ]

];
