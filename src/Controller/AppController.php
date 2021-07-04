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
        $authorization = $this->request->getAttribute('authorization');

        // set token
        $this->token = (isset($authorization['token']))?$authorization['token']:null;

        // set payload
        $this->payload = (isset($authorization['payload']))?$authorization['payload']:null;


        parent::initialize();
    }
}
