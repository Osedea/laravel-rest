<?php

namespace Osedea\LaravelRest\Exceptions;

use Exception;

class InvalidFilterException extends Exception
{
    public function __construct($message = 'Invalid filter used in url params.', $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
