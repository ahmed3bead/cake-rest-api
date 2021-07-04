<?php

use Cake\Utility\Security;


return [
    'CakeRestApi' => [
        'jwt' => [
            'key' => Security::getSalt(),
            'algorithm' => 'HS256'
        ],
        'useRestErrorHandler' => true,
        'useApiAuth' => true,
    ]

];
