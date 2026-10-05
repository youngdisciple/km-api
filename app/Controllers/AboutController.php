<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AboutController extends BaseController
{
    private const API_NAME = 'km-api';

    private const API_VERSION = '1.0.0';

    public function handleAboutWebService(Request $request, Response $response): Response
    {
        $data = [
            'api' => self::API_NAME,
            'version' => self::API_VERSION,
            'about' => 'This is an api that provides information about keyboards and mices',
            'author' => 'Karim Bennis',
            'resources' => [
                'vendor' => [
                    [
                        'path' => '/vendors',
                        'methods' => [
                            'GET' => 'List all vendors'
                            // TODO: eventually, add different methods like POST
                            // and describe what they would do
                        ]
                    ],
                    [
                        'path' => '/vendors/{vendor_id}',
                        'methods' => [
                            'GET' => 'Show details about a vendor'
                        ]
                    ],
                    [
                        'path' => '/vendors/{vendor_id}/switches',
                        'methods' => [
                            'GET' => 'List all switches tied to a specific vendor'
                        ]
                    ],
                ],
                'keyboard' => [
                    [
                        'path' => '/keyboards',
                        'methods' => [
                            'GET' => 'List all keyboards'
                        ]
                    ],
                    [
                        'path' => '/keyboards/{keyboard_id}',
                        'methods' => [
                            'GET' => 'Show details about a keyboard'
                        ]
                    ],
                    [
                        'path' => '/keyboards/{keyboard_id}/reviews',
                        'methods' => [
                            'GET' => 'List all reviews tied to a specific keyboard'
                        ]
                    ],
                ]
            ]
        ];

        return $this->renderJson($response, $data);
    }
}
