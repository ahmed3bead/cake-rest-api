<?php

namespace CakeRestApi\Controller;

use Cake\Event\EventInterface;


class RestController extends AppController
{


    // Within your controllers
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('CakeRestApi.ApiPagination');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('CakeRestApi.ApiPagination', [
            'key' => 'paging',
            'aliases' => [
                'page' => 'currentPage',
                'current' => 'resultCount',
                'pageCount' =>'pageCount'
            ],
            'visible' => [
                'currentPage',
                'resultCount',
                'prevPage',
                'nextPage',
                'pageCount'
            ],
            // 'model' => 'Articles',
        ]);

        // $this->viewBuilder()->setOption('serialize', true);
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
