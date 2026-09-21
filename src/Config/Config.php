<?php

namespace alf89\HttpClient\Config;

readonly class Config
{

    public function __construct(
        private int    $timeout = 30,
        private int    $connectTimeout = 5,
        private string $userAgent = 'alf89/http-client/1.0',
        private bool   $verifyPeer = true,
    ){}


    public function getTimeout(): int
    {
       return $this->timeout;
    }
    public function getConnectTimeout(): int{
        return $this->connectTimeout;
    }
    public function getUserAgent(): string{
        return $this->userAgent;
    }
    public function getVerifyPeer(): bool{
        return $this->verifyPeer;
    }
}