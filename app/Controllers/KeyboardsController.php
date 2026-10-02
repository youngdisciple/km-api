<?php

namespace App\Controllers;

use App\Domain\Models\KeyboardsModel;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class KeyboardsController extends BaseController
{
    function __construct(
        private KeyboardsModel $keyboardsModel
    )
    {

    }

    public function index(Request $request, Response $response) : Response {
        $keyboards = $this->keyboardsModel->getKeyboards();

        return $this->renderJson($response, $keyboards);
    }

    public function show(Request $request, Response $response, array $args) : Response {
        $keyboard_id = $args['keyboard_id'];

        return $response;
    }
}
