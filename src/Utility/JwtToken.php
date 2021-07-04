<?php

namespace CakeRestApi\Utility;

use Cake\Core\Configure;
use Firebase\JWT\JWT;

/**
 * JWT token utility class.
 */
class JwtToken
{

    /**
     * Generates a token
     *
     * @param mixed $payload Payload data to generate token
     * @return string|bool Token or false
     */
    public static function generate($payload = null)
    {
        if (empty($payload)) {
            return false;
        }

        $token = JWT::encode($payload, Configure::read('CakeRestApi.jwt.key'), Configure::read('CakeRestApi.jwt.algorithm'));

        return $token;
    }
}
