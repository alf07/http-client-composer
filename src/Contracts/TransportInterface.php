<?php

namespace alf89\HttpClient\Contracts;

interface TransportInterface
{
    public function send(
        string $method,
        string $url,
        array $headers = [],
        array $body = [],
        array $query = []
    );
}