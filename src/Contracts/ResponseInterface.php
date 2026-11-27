<?php

namespace alf89\HttpClient\Contracts;

interface ResponseInterface
{
    public function getStatusCode(): int;
    /**
     * @return array<string, string>
     */
    public function getHeaders(): array;
    public function getBody(): string;
}