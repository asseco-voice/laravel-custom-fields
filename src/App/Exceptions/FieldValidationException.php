<?php

namespace Asseco\CustomFields\App\Exceptions;

use Exception;
use Throwable;

class FieldValidationException extends Exception
{
    private array $data = [];

    public function __construct(string $message = 'Invalid form', int $code = 400, ?Throwable $previous = null, array $data = [])
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
