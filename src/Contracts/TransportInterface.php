<?php

namespace alf89\HttpClient\Contracts;

interface TransportInterface
{
    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $body
     * @param array<array-key, mixed> $query
     */
    public function send(
        string $method,
        string $url,
        array $headers = [],
        array $body = [],
        array $query = []
    ): ResponseInterface;
}