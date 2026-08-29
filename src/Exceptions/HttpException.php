<?php

namespace alf89\HttpClient\Exceptions;

use RuntimeException;

final class HttpException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly int $statusCode,
        private readonly string $statusBody,
    )
    {
        parent::__construct($message, $statusCode);
    }
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    public function getBody(): string
    {
        return $this->statusBody;
    }

}