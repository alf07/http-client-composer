<?php

namespace alf89\HttpClient\Exceptions;

use RuntimeException;

class ConnectionException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly int $curlErrorCode,
    )
    {
        parent::__construct($message, $curlErrorCode);
    }

    public function getCurlErrorCode(): int
    {
        return $this->curlErrorCode;
    }
}