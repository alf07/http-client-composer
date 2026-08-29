<?php

namespace alf89\HttpClient\Transport;

use alf89\HttpClient\Contracts\TransportInterface;
use alf89\HttpClient\Exceptions\ConnectionException;
use alf89\HttpClient\Http\Response;

/**\
 * @todo TIMEOUT  передавать или если не передано устанавливать по умолчанию
 * @todo CONNECTTIMEOUT  передавать или если не передано устанавливать по умолчанию
 * @todo USERAGENT в переменную
 * @todo почитать какие параметры можно еще добавить
 */
class CurlTransport implements TransportInterface
{
    public function send(string $method, string $url, array $headers = [], array $body = [], array $query = []): Response
    {
        if($query !== []){
            $url .= '?'.http_build_query($query);
        }

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_USERAGENT => 'alf89/http-client/1.0',
            CURLOPT_SSL_VERIFYPEER => true,
        ];

        if($body !== []){
            /**
             * @todo добавить обработку ошибки json_encode
             */
            $options[CURLOPT_POSTFIELDS] = json_encode($body);
        }

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        /**
         * @todo доработать ошибку (http код и тело)
         */
        if($response === false){
            throw new ConnectionException(
                curl_error($curl),
                curl_errno($curl)
            );
        }

        $responseSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        $responseStatusCode =  curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $responseHeaders = substr($response, 0, $responseSize);
        $arrHeaders = $this->parseHeaders($responseHeaders);
        $responseBody = substr($response, $responseSize);

        return new Response(
            statusCode: $responseStatusCode,
            headers: $arrHeaders,
            body: $responseBody,
        );

    }
    private function parseHeaders($responseHeaders): array
    {
        preg_match_all(
            '/^([^:\r\n]+):\s*(.*)$/m',
            $responseHeaders,
            $matches,
        );

        return array_combine(
            array_map('trim', $matches[1]),
            array_map('trim', $matches[2])
        );
    }
}