<?php

namespace CakeRestApi\Controller;

use Cake\Event\Event;

class CakeRestApiController extends AppController
{

    /**
     * beforeRender callback
     *
     * @param Event $event An Event instance
     * @return null
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->viewBuilder()->setClassName('CakeRestApi.Json');

        return null;
    }
}
