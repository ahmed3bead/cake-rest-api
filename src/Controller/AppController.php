<?php

namespace CakeRestApi\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{

    /**
     * Token
     *
     * @var string
     */
    public $token = "";

    /**
     * Payload data decoded from token
     *
     * @var mixed
     */
    public $payload = [];

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize():void
    {
       

        parent::initialize();
    }
}
