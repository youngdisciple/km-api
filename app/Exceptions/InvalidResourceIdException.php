<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

// NOTE: An HTTP specialized exception class.
// Details: https://ws.frostybee.dev/implementation/error-handling/slim-http-exceptions/#creating-custom-http-exceptions
class InvalidResourceIdException extends HttpSpecializedException
{
    // Fields to be overridden
    protected int $code = 400;
    protected string $message = 'Bad request'; // NOTE: HTTP Reason phrase
    protected string $title = '400 Bad request';
    protected string $description = 'The provided resource ID was invalid';
}
