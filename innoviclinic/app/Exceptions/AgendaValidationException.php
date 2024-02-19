<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class AgendaValidationException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public function render()
    {
        return response()->json(['message' => $this->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
