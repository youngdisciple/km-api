<?php

namespace App\Controllers;

use App\Domain\Models\KeyboardsModel;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class KeyboardsController extends BaseController
{
    function __construct(
        private KeyboardsModel $keyboardsModel
    )
    {

    }

    /**
    * Controller method that renders a list of keyboards
    *
    * @return Response A response object with the rendered list of keyboards
    */
    public function index(Request $request, Response $response) : Response
    {
        // TODO: add filter functionality
        $keyboards = $this->keyboardsModel->getKeyboards();

        return $this->renderJson($response, $keyboards);
    }

    /**
    * Controller method that renders a specific keyboard by it's keyboard_id.
    *
    * @param array $args Takes in the keyboard's id via the key 'keyboard_id'
    *
    * @return Response A response object with the rendered keyboard.
    */
    public function show(Request $request, Response $response, array $args) : Response
    {
        $keyboard_id = $args['keyboard_id'];

        $pattern = "/^\d{1,9}$/";
        if (preg_match($pattern, $keyboard_id) === 0) {
            throw new HttpBadRequestException(
                $request,
                "The received keyboard_id ($keyboard_id) is invalid"
            );
        }

        $keyboard = $this->keyboardsModel->getKeyboardById($keyboard_id);

        if (!$keyboard) {
            throw new HttpNotFoundException($request);
        }

        return $this->renderJson($response, $keyboard);
    }

    /**
    * Controller method that renders a list of reviews that a specific keyboard has
    *
    * @param array $args Takes in the keyboard's id via the key 'keyboard_id'
    *
    * @return Response A response object with the rendered list of keyboard.
    */
    public function handleGetKeyboardReviews(Request $request, Response $response, array $args) : Response
    {
        $keyboard_id = $args['keyboard_id'];

        $pattern = "/^\d{1,9}$/";
        if (preg_match($pattern, $keyboard_id) === 0) {
            throw new HttpBadRequestException(
                $request,
                "The received keyboard_id ($keyboard_id) is invalid"
            );
        }

        $reviews = $this->keyboardsModel->getKeyboardReviews($keyboard_id);

        if (!$reviews) {
            throw new HttpNotFoundException($request);
        }

        return $this->renderJson($response, $reviews);
    }
}
