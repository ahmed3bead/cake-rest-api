<?php

namespace CakeRestApi\Error;

use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Error\ExceptionRenderer;
use Cake\Log\Log;
use CakeRestApi\Controller\ErrorController;
use Psr\Http\Message\ResponseInterface;


class RestExceptionRenderer extends ExceptionRenderer
{
    // /**
    //  * Renders the response for the exception.
    //  *
    //  * @return \Cake\Http\Response The response to be sent.
    //  */
    public function render(): ResponseInterface
    {

        $exception = $this->error;
        $code = $this->getHttpCode($exception);
        $method = $this->_method($exception);
        $template = $this->_template($exception, $method, $code);
        $this->clearOutput();

        // $exception = $this->error;
        // $code = $this->_code($exception);

        $unwrapped = $this->_unwrap($exception);

        if ($exception instanceof \CakeRestApi\Routing\Exception\MissingTokenException ||
            $exception instanceof \CakeRestApi\Routing\Exception\InvalidTokenException ||
            $exception instanceof \CakeRestApi\Routing\Exception\InvalidTokenFormatException
        ) {
            $message = $exception->getMessage();
        } else {
            $message = $this->_message($exception, $code);
        }

        $response = $this->controller->getResponse();

        if ($exception instanceof CakeException) {
            foreach ((array)$exception->responseHeader() as $key => $value) {
                $response = $response->withHeader($key, $value);
            }
        }
        $response = $response->withStatus($code);

        $viewVars = [
            'message' => $message,
            'code' => $code
        ];

        $isDebug = Configure::read('debug');

        if ($isDebug) {
            $viewVars['trace'] = Debugger::formatTrace($unwrapped->getTrace(), [
                    'format' => 'array',
                    'args' => false
            ]);
            $viewVars['file'] = $exception->getFile() ? : 'null';
            $viewVars['line'] = $exception->getLine() ? : 'null';
        }

        $this->controller->set($viewVars);

        if ($unwrapped instanceof CakeException && $isDebug) {
            $this->controller->set($unwrapped->getAttributes());
        }

        $this->controller->response = $response;

        return $this->_prepareResponse();
    }


    /**
     * Renders the response for the exception.
     *
     * @return \Cake\Http\Response The response to be sent.
     */
    // public function render(): ResponseInterface
    // {
    //     $exception = $this->error;
    //     $code = $this->getHttpCode($exception);
    //     $method = $this->_method($exception);
    //     $template = $this->_template($exception, $method, $code);
    //     $this->clearOutput();

    //     if (method_exists($this, $method)) {
    //         return $this->_customMethod($method, $exception);
    //     }

    //     $message = $this->_message($exception, $code);
    //     $url = $this->controller->getRequest()->getRequestTarget();
    //     $response = $this->controller->getResponse();

    //     if ($exception instanceof CakeException) {
    //         /** @psalm-suppress DeprecatedMethod */
    //         foreach ((array)$exception->responseHeader() as $key => $value) {
    //             $response = $response->withHeader($key, $value);
    //         }
    //     }
    //     if ($exception instanceof HttpException) {
    //         foreach ($exception->getHeaders() as $name => $value) {
    //             $response = $response->withHeader($name, $value);
    //         }
    //     }
    //     $response = $response->withStatus($code);

    //     $viewVars = [
    //         'message' => $message,
    //         'url' => h($url),
    //         'error' => $exception,
    //         'code' => $code,
    //     ];
    //     $serialize = ['message', 'url', 'code'];

    //     $isDebug = Configure::read('debug');
    //     if ($isDebug) {
    //         $trace = (array)Debugger::formatTrace($exception->getTrace(), [
    //             'format' => 'array',
    //             'args' => false,
    //         ]);
    //         $origin = [
    //             'file' => $exception->getFile() ?: 'null',
    //             'line' => $exception->getLine() ?: 'null',
    //         ];
    //         // Traces don't include the origin file/line.
    //         array_unshift($trace, $origin);
    //         $viewVars['trace'] = $trace;
    //         $viewVars += $origin;
    //         $serialize[] = 'file';
    //         $serialize[] = 'line';
    //     }
    //     $this->controller->set($viewVars);
    //     $this->controller->viewBuilder()->setOption('serialize', $serialize);

    //     if ($exception instanceof CakeException && $isDebug) {
    //         $this->controller->set($exception->getAttributes());
    //     }
    //     $this->controller->setResponse($response);

    //     return $this->_outputMessage($template);
    // }


    /**
     * Generates the response using the controller object.
     *
     * @return \Cake\Http\Response A response object that can be sent.
     */
    protected function _prepareResponse()
    {
        $this->controller->viewBuilder()->setClassName('CakeRestApi.Json');

        $this->controller->render();

        return $this->_shutdown();
    }
}
