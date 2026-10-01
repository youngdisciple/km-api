<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

// NOTE: An HTTP specialized exception class.
// Details: https://ws.frostybee.dev/implementation/error-handling/slim-http-exceptions/#creating-custom-http-exceptions
class HttpNotAcceptableException extends HttpSpecializedException
{
    // Fields to be overridden
    protected $code = 406;
    protected $message = 'Not Acceptable'; // NOTE: HTTP Reason phrase
    protected string $title = '406 Not Acceptable';
    protected string $description = "The server cannot produce a response matching the criteria defined in the request's content negotiation headers.";
}
