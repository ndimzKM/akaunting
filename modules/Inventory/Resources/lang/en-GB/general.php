<?php

return [

    'name'              => 'Inventory',
    'description'       => 'This is my awesome module',
    'units'             => [
        'name'      => 'Units',
        'form_description' => [
            'general' =>  'I am under the water.'
        ]
    ],
    'inventory' => [
        'units' => 'Unit',
    ],
    'empty'             => [
        'inventory' => [
            'units' => "Units define the measurement for items, such as pieces, kilograms, or hours.",
        ],
        'actions' => [
            'new' => 'fadlkj'
        ]
    ]
];
