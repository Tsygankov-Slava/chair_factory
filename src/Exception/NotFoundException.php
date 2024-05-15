<?php

namespace App\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends RuntimeException
{
    public function __construct(string $message = "")
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }

}
