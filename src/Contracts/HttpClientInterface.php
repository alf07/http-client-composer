<?php

namespace alf89\HttpClient\Contracts;

interface HttpClientInterface
{
    public function get(
        string $url,
        array $headers = [],
        array $query = []
    );

    public function post(
        string $url,
        array $headers = [],
        array $body = []
    );

    public function put(
        string $url,
        array $headers,
        array $body = []
    );

    public function patch(
        string $url,
        array $headers,
        array $body = []
    );

    public function delete(
        string $url,
        array $headers,
        array $query  = []
    );
}