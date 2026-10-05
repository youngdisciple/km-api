<?php

namespace App\Controllers;

use App\Domain\Models\VendorsModel;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;
use App\Exceptions\HttpNotAcceptableException;

class VendorsController extends BaseController
{
    // approach 1 (creating an instance):
    // e.g. private VendorsModel $vendorsModel;

    // approach 2 (DI Injector, better):
    // because the vendorsModel doesn't necessitate any complicated params, the php-di extension automatically takes
    // care of it. However, if it were the case, you would define something inside of container.php
    function __construct( private VendorsModel $vendorsModel )
    {

    }

    // NOTE: The phpdoc here doesn't need a @param for $request nor $reponse because
    // it is handled automatically and is self explanatory.
    /**
    * Controller method that renders a list of vendors
    *
    * @return Response A response object with the rendered list of vendors
    */
    public function index(Request $request, Response $response) : Response {

        $filters = $request->getQueryParams();

        if (!empty($filters)){
            //! VALIDATE THE FILTER VALUES, i.e., both should be positive integers
            $this->vendorsModel->setPaginationOptions(
                $filters['page'],
                $filters['page_size']
            );
        }

        // 1) Fetch the list of vendors from the DB
        $vendors = $this->vendorsModel->getVendors($filters);

        // 2) Prepare a JSON Response. MANUAL

        // 2.1) converts into JSON string.
        // $data = json_encode($vendors);

        // 2.2) write the JSON string into the Response body
        // $response->getBody()->write($data);

        // MAKE SURE TO STAMP THE DATATYPE (in this case, JSON)
        // so that clients like nouto can format this beautifully :3
        // return $response->withHeader(
        //     "Content-Type", "application/json;charset=UTF-8"
        // );

        // AUTO (base controller)
        return $this->renderJson($response, $vendors);
    }

    /**
    * Controller method that renders a specific vendor by it's vendor_id.
    *
    * @param array $args Takes in the vendor's id via the key 'vendor_id'
    *
    * @return Response A response object with the rendered vendor.
    */
    public function show(Request $request, Response $response, array $args): Response
    {
        $vendor_id = $args['vendor_id'];

        //! Alternative path: edge cases, i.e., the 'what if' questions:
        //! 1) Invalid input
        //! 2) Situational errors, e.g. id format is valid but does not match any entry within the DB (=> 404)
        //! 3) Invalid credentials (=> 403)

        // Early return strategy
        $pattern = "/^\d{1,9}$/";
        if (preg_match($pattern, $vendor_id) === 0) {
            // option 1: Using an HTTPSpecializedException given by slim framework. (=> 400, bad request)
            // throw new HttpBadRequestException(
            //     $request,
            //     "The id you gave is no bueno pal."
            // );

            // DONE

            // or:

            // option 2: Prepare and return the HTTP error response yourself

            //manual:
            $error_data = [
                'status' => 'error',
                'code' => '400',
                'message' => "The received vendor_id ($vendor_id) is no bueno."
            ];

            return $this->renderJson($response, $error_data);
        }

        $vendor = $this->vendorsModel->getVendorsById($vendor_id);
        //! what if the vendor was False: i.e. NOT FOUND?
        // TODO: Prepare and return a VALID HTTP error response (option #1)
        if (!$vendor) {

            throw new HttpNotFoundException(
                $request,
                "There was no matching record for vendor_id ($vendor_id)"
            );
        }

        // manual:
        // $data =json_encode($vendor);

        // $response->getBody()->write($data);

        // return $response->withHeader(
        //     "Content-Type", "application/json"
        // );

        return $this->renderJson($response, $vendor);
    }

    /**
    * Controller method that renders a list of switches that a specific vendor sells
    *
    * @param array $args Takes in the vendor's id via the key 'vendor_id'
    *
    * @return Response A response object with the rendered list of switches.
    */
    public function handleGetVendorSwitches(Request $request, Response $response, array $args): Response
    {
        $vendor_id = $args['vendor_id'];

        $pattern = "/^\d{1,9}$/";
        if (preg_match($pattern, $vendor_id) === 0) {
            throw new HttpNotAcceptableException(
                $request,
                "The provided vendor_id ($vendor_id) could not be validated."
            );
        }

        $switches = $this->vendorsModel->getVendorSwitches($vendor_id);

        // NOTE: $switches returning nothing is not a concern and will not
        // trigger this conditional statement
        if(!$switches) {
            throw new HttpNotFoundException($request);
        }

        // NOTE: No need to specify status code here as renderJson defaults to
        // statuscode = 200
        return $this->renderJson($response, $switches);
    }
}
