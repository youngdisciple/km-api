<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

// NOTE: An HTTP specialized exception class.
// Details: https://ws.frostybee.dev/implementation/error-handling/slim-http-exceptions/#creating-custom-http-exceptions
class HttpNotAcceptableException extends HttpSpecializedException
{
    // Fields to be overridden
    protected $code = 415;
    protected $message = 'Unsupported Media'; // NOTE: HTTP Reason phrase
    protected string $title = '415 Unsupported Media';
    protected string $description = "The client sent a Content-Type that the service cannot parse";
}
