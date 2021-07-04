<?php

use Cake\Event\EventManager;
use CakeRestApi\Middleware\AuthorizationMiddleware;
use CakeRestApi\Middleware\RestErrorMiddleware;
use CakeRestApi\Middleware\RestMiddleware;





EventManager::instance()->on(
    'Server.buildMiddleware',
    function ($event, $middlewareQueue) {


        $middlewareQueue->insertAfter(
            'Cake\Routing\Middleware\RoutingMiddleware',
            new RestMiddleware()
        );

        $middlewareQueue->insertAfter(
            'CakeRestApi\Middleware\RestMiddleware',
            new RestErrorMiddleware()
        );

        // $middlewareQueue->insertAfter(
        //     'CakeRestApi\Middleware\RestErrorMiddleware',
        //     new AuthorizationMiddleware()
        // );

      
    }
);

/*
 * Read and inject configuration
 */
try {
    Cake\Core\Configure::load('CakeRestApi.rest', 'default', false);
    Cake\Core\Configure::load('rest', 'default', true);
} catch (\Exception $e) {
    // do nothing
}
