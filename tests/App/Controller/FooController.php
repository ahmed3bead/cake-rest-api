<?php

namespace App\Controller;

use CakeRestApi\Controller\RestController;

/**
 * Foo Controller
 *
 */
class FooController extends CakeRestApiController
{

    /**
     * bar method
     *
     * @return Response|void
     */
    public function bar()
    {
        $bar = [
            'falanu' => [
                'dhikanu',
                'tamburo'
            ]
        ];

        $this->set(compact('bar'));
    }

    /**
     * doe method
     *
     * @return Response|void
     */
    public function doe()
    {
        $data = [
            'requireToken' => true
        ];

        $this->set(compact('data'));
    }
}
