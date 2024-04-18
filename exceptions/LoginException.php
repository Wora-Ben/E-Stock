<?php

namespace exceptions;

/**
 * LoginException
 */
class LoginException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Login failed : " . $message);
    }

}