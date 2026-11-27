<?php

namespace alf89\HttpClient\Contracts;

interface HttpClientInterface
{
    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $query
     */
    public function get(
        string $url,
        array $headers = [],
        array $query = []
    ): ResponseInterface;

    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $body
     */
    public function post(
        string $url,
        array $headers = [],
        array $body = []
    ): ResponseInterface;

    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $body
     */
    public function put(
        string $url,
        array $headers,
        array $body = []
    ): ResponseInterface;

    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $body
     */
    public function patch(
        string $url,
        array $headers,
        array $body = []
    ): ResponseInterface;

    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $query
     */
    public function delete(
        string $url,
        array $headers,
        array $query  = []
    ): ResponseInterface;
}