<?php

namespace CakeRestApi\Middleware;

use Cake\Core\Configure;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use CakeRestApi\Routing\Exception\InvalidTokenException;
use CakeRestApi\Routing\Exception\InvalidTokenFormatException;
use CakeRestApi\Routing\Exception\MissingTokenException;

/**
 * Authorization middleware
 */
class AuthorizationMiddleware
{

    /**
     * Invoke method.
     *
     * @param ServerRequestInterface $request The request.
     * @param ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return ResponseInterface A response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $params = (array)$request->getAttribute('params', []);

        if (!isset($params['isRest']) || !$params['isRest']) {
            return $next($request, $response);
        }

        if (isset($params['requireAuthorization']) && $params['requireAuthorization']) {
            $header = $request->getHeaderLine('Authorization');
            $qParams = $request->getQueryParams();
            $postData = $request->getParsedBody();

            if (!empty($header)) {
                $parts = explode(' ', $header);

                if (count($parts) < 2 || empty($parts[0]) || !preg_match('/^Bearer$/i', $parts[0])) {
                    throw new InvalidTokenFormatException();
                }

                $token = $parts[1];
            } elseif (!empty($qParams['token'])) {
                $token = $qParams['token'];
                unset($qParams);
            } elseif (!empty($postData['token'])) {
                $token = $postData['token'];
                unset($postData);
            } else {

                die(json_encode(['code'=>'401','message'=>'Token is missing. Please pass the token in request in the form of header, query parameter or post data field.']));
            }

            try {
                $payload = JWT::decode($token, Configure::read('CakeRestApi.jwt.key'), [Configure::read('CakeRestApi.jwt.algorithm')]);
            } catch (\Exception $e) {
                die(json_encode(['code'=>'401','message'=>'Invalid or empty token.']));

            }

            if (empty($payload)) {
                // throw new InvalidTokenException();
                die(json_encode(['code'=>'401','message'=>'Invalid or empty token.']));

            }

            $authorizationAttr = [
                'token' => $token,
                'payload' => $payload
            ];

            $request = $request->withAttribute('authorization', $authorizationAttr);
        }

        return $next($request, $response);
    }
}
