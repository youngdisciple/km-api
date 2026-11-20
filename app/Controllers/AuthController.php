<?php

namespace App\Controllers;

use App\Helpers\Core\AppSettings;
use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthController extends BaseController
{
    public function __construct(private AppSettings $appSettings)
    {
    }
    // TODO: Add the handleGenerateToken callback
    // GET|POST /token-test
    public function handleGenerateToken(Request $request, Response $response) : Response
    {
        // secret, algo, expiry, payload
        // step 1: generate the token
        $jwtSettings = $this->appSettings->get('jwt');
        $secret = $jwtSettings['secret'];
        $algorithm = $jwtSettings['algorithm'];
        $expiry = $jwtSettings['expiry'];

        $payload = [
            'iss' => 'km-api',
            'aud' => 'keyboard enthusiasts',
            'iat' => time(),
            'exp' => time() +  $expiry,
            // TODO: Add private claims (email, role, id, etc...)
            'user_id' => '1234',
            'email' => 'boss@gmail.com'
        ];

        // add the JWT::encode() method

        $jwt = JWT::encode($payload, $secret, $algorithm);

        // step 2: return a well-structured JSON response
        /*
        *   {
        *       "status": "success",
        *       "code": 200,
        *       "jwt": <TOKEN>
        *   }
        */

        // step 3:
        return $this->renderJson($response, [
            'status' => "success",
            'code' => '200',
            'jwt' => $jwt
        ], 200);
    }
}
