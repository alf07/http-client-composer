<?php

namespace alf89\HttpClient\Facades;

use alf89\HttpClient\Services\HttpClientService;
use alf89\HttpClient\Transport\CurlTransport;

final class Http
{
    private static ?HttpClientService $service = null;

    private  static function service(): HttpClientService
    {
        return self::$service ??= new HttpClientService(
            new CurlTransport()
        );
    }

    public static function get(
        string $url,
        array $headers = [],
        array $query = []
    )
    {
        return self::service()->get(
            $url,
            $headers,
            $query
        );
    }

    public static function post(
        string $url,
        array $headers = [],
        array $body = []
    )
    {
        return self::service()->post(
            $url,
            $headers,
            $body
        );
    }

    public static function put(
        string $url,
        array $headers = [],
        array $body = []
    )
    {
        return self::service()->put(
            $url,
            $headers,
            $body
        );
    }

    public static function delete(
        string $url,
        array $headers = [],
        array $query = []
    )
    {
        return self::service()->delete(
            $url,
            $headers,
            $query
        );
    }

    public static function patch(
        string $url,
        array $headers = [],
        array $body = []
    )
    {
        return self::service()->patch(
            $url,
            $headers,
            $body
        );
    }
}