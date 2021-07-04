<?php

namespace CakeRestApi\Controller;

use Cake\Event\EventInterface;
use Cake\Core\Configure;


class RestController extends AppController
{




    public function initialize(): void
    {
        parent::initialize();
        
        $this->loadComponent('Authentication.Authentication', [
            'requireIdentity' => false
        ]);
        $this->Authentication->allowUnauthenticated(['login']);

    }

    public function beforeFilter(EventInterface $event)
    {


        
       

        $action = $this->request->getParam('action');

        if (
            !in_array($action, $this->Authentication->getUnauthenticatedActions(), true) &&
            !$this->Authentication->getIdentity()
        ) {

            $this->response = $this->response->withStatus(401);
            $json = [
                'code' => 401,
                'message' => 'You must be logged in to access this page.'
            ];
            $this->set(compact('json'));
            $this->viewBuilder()->setOption('serialize', 'json');
            die(json_encode($json));

            parent::beforeFilter($event);
        }



        // throw new UnauthenticatedException('You must be logged in to access this page.');
    }



    /**
     * beforeRender callback
     *
     * @param Event $event An Event instance
     * @return null
     */
    public function beforeRender(EventInterface $event)
    {


        parent::beforeRender($event);

        $this->viewBuilder()->setClassName('CakeRestApi.Json');

        return null;
    }
}
