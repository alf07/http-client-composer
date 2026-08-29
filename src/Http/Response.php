<?php

namespace alf89\HttpClient\Http;

use alf89\HttpClient\Contracts\ResponseInterface;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        private int    $statusCode,
        private array  $headers,
        private string $body,
    ){}

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
