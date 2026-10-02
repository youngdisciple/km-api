<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\KeyboardsController;
use App\Controllers\VendorsController;
use App\Helpers\DateTimeHelper;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return static function (Slim\App $app): void {

    // Routes without authentication check: /login, /token

    // ROUTE: GET /

    // TODO:
    // Provide a root resource (that is, / ) whose content includes all the resources the WS exposes
    // along with the full URI for each resource and their respective description.
    $app->get('/', [AboutController::class, 'handleAboutWebService']);

    // ROUTE: /vendors

    // GET /vendors
    $app->get('/vendors', [VendorsController::class, 'index']);

    // GET /vendors/{vendor_id}
    $app->get('/vendors/{vendor_id}', [VendorsController::class, 'show']);

    // GET /vendors/{vendor_id}/switches
    $app->get('/vendors/{vendor_id}/switches', [VendorsController::class, 'handleGetVendorSwitches']);

    // ROUTE: /keyboards

    // GET /keyboards
    $app->get('/keyboards', [KeyboardsController::class, 'index']);

    // GET /keyboards/{keyboard_id}
    $app->get('/keyboards/{keyboard_id}', [KeyboardsController::class, 'show']);

    // NOTE: callback naming pattern: handle<ActionName>, e.g. handleGetPlayers
    // ROUTE: GET /players
    //$app->get('/players', [PlayersController::class, 'handleGetPlayers']);

    //* ROUTE: GET /ping
    $app->get('/ping', function (Request $request, Response $response, $args) {

        $payload = [
            "greetings" => "Reporting! Hello there!",
            "now" => DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M),
        ];
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR));
        return $response;
    });

    // Example route to test error handling.
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });

    //* ROUTE: GET /phpinfo -> Display PHP configuration (useful for Docker)
    //* Docker URL: http://localhost:8080/phpinfo
    $app->get('/phpinfo', function (Request $request, Response $response, $args) {
        ob_start();
        phpinfo();
        $info = ob_get_clean();
        $response->getBody()->write($info);
        return $response->withHeader('Content-Type', 'text/html');
    });
};
