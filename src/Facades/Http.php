<?php

namespace alf89\HttpClient\Facades;

use alf89\HttpClient\Config\Config;
use alf89\HttpClient\Contracts\ResponseInterface;
use alf89\HttpClient\Services\HttpClientService;
use alf89\HttpClient\Transport\CurlTransport;

final class Http
{

    private static ?HttpClientService $service = null;
    private static ?Config $config = null;

    private  static function service(): HttpClientService
    {
        return self::$service ??= new HttpClientService(
            new CurlTransport(self::config())
        );
    }

    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $query
     */
    public static function get(
        string $url,
        array $headers = [],
        array $query = []
    ):ResponseInterface
    {
        return self::service()->get(
            $url,
            $headers,
            $query
        );
    }

    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $body
     */
    public static function post(
        string $url,
        array $headers = [],
        array $body = []
    ): ResponseInterface
    {
        return self::service()->post(
            $url,
            $headers,
            $body
        );
    }

    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $body
     */
    public static function put(
        string $url,
        array $headers = [],
        array $body = []
    ): ResponseInterface
    {
        return self::service()->put(
            $url,
            $headers,
            $body
        );
    }

    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $query
     */
    public static function delete(
        string $url,
        array $headers = [],
        array $query = []
    ): ResponseInterface
    {
        return self::service()->delete(
            $url,
            $headers,
            $query
        );
    }

    /**
     * @param list<string> $headers
     * @param array<array-key, mixed> $body
     */
    public static function patch(
        string $url,
        array $headers = [],
        array $body = []
    ): ResponseInterface
    {
        return self::service()->patch(
            $url,
            $headers,
            $body
        );
    }

    public static function config(): Config
    {
        return  self::$config ??= new Config();
    }
}