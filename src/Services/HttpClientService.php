<?php

namespace alf89\HttpClient\Services;

use alf89\HttpClient\Contracts\HttpClientInterface;
use alf89\HttpClient\Contracts\TransportInterface;

readonly class HttpClientService implements HttpClientInterface
{

    public function __construct(
        private TransportInterface $transport
    ) {}

    
    public function get(string $url, array $headers = [], array $query = [])
    {
       return $this->transport->send(
            method:'GET',
            url: $url,
            headers: $headers,
            query: $query
       );
    }

    public function post(string $url, array $headers = [], array $body = [])
    {
        return $this->transport->send(
            method: 'POST',
            url: $url,
            headers: $headers,
            body: $body
        );
    }

    public function put(string $url, array $headers = [], array $body = [])
    {
        return $this->transport->send(
            method: 'PUT',
            url: $url,
            headers: $headers,
            body: $body
        );
    }

    public function delete(string $url, array $headers = [], array $query = [])
    {
        return $this->transport->send(
            method: 'DELETE',
            url: $url,
            headers: $headers,
            query: $query
        );
    }

    public function patch(string $url, array $headers = [], array $body = [])
    {
        return $this->transport->send(
            method:'PATCH',
            url: $url,
            headers: $headers,
            body: $body
        );
    }
}