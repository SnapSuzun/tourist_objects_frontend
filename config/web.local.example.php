<?php

return [
    'components' => [
        'assetManager' => [
            'bundles' => [
                'dosamigos\google\maps\MapAsset' => [
                    'options' => [
                        'key' => 'api_key_id'
                    ]
                ],
                'app\assets\GoogleMapAsset' => [
                    'options' => [
                        'key' => 'api_key_id',
                        'libraries' => 'places'
                    ]
                ],
            ]
        ],
    ]
];