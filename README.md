<img width="1536" height="1024" alt="http_client" src="https://github.com/user-attachments/assets/f1b0e5f7-19b0-4bcc-8ddc-3381be48edda" />

# HTTP Client

Легковесный HTTP-клиент для PHP без зависимости фреймворков.

Пакет предоставляет простой интерфейс для выполнения HTTP-запросов через PHP cURL.

## Возможности

* GET
* POST
* PUT
* PATCH
* DELETE
* Query parameters
* HTTP headers
* JSON request body
* Получение HTTP status code
* Получение response headers
* Получение response body
* Обработка ошибок cURL
* `Facade` для удобного использования
* Разделение HTTP-клиента и transport-слоя через интерфейсы
* PSR-4 autoloading
* Управление проверкой SSL-сертификата
* Настройка таймаута подключения
* Настройка таймаута запроса
* Конфигурация HTTP-клиента через Config

## Требования

* PHP 8.5+
* PHP extension `ext-curl`

## Установка

Установите пакет через Composer:

```bash
composer require alf89/http-client
```

## Конфигурация

Конфигурация HTTP-клиента создаётся автоматически и доступна через `Http::config()`.

```php
use alf89\HttpClient\Facades\Http;

Http::config()->timeout = 60;
Http::config()->connectTimeout = 10;
Http::config()->verifyPeer = false;
```

Доступные параметры:

```php
Http::config()->timeout;
Http::config()->connectTimeout;
Http::config()->userAgent;
Http::config()->verifyPeer;
```

Значения по умолчанию:

```text
timeout        = 30
connectTimeout = 5
userAgent      = alf89/http-client/0.2.1
verifyPeer     = true
```

`timeout` и `connectTimeout` должны быть положительными целыми числами.

```php
Http::config()->timeout = 0;
// InvalidArgumentException
```

`userAgent` доступен для чтения, но не предназначен для изменения через конфигурацию.

## Использование

Для работы с HTTP-клиентом используется `Http` Facade.

Создавать `HttpClientService`, `CurlTransport` или другие внутренние компоненты вручную не требуется.

### GET

Простой GET-запрос:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use alf89\HttpClient\Facades\Http;

$response = Http::get(
    'https://httpbin.org/get'
);

echo $response->getStatusCode();
```

### GET с headers

```php
$response = Http::get(
    'https://httpbin.org/get',
    [
        'Accept: application/json',
    ]
);
```

### GET с query parameters

```php
$response = Http::get(
    'https://httpbin.org/get',
    [
        'Accept: application/json',
    ],
    [
        'page' => 2,
        'limit' => 10,
    ]
);
```

В результате будет выполнен запрос:

```text
https://httpbin.org/get?page=2&limit=10
```

### POST

В текущей версии `POST` отправляет тело запроса в формате JSON.

```php
$response = Http::post(
    'https://httpbin.org/post',
    [
        'Accept: application/json',
        'Content-Type: application/json',
    ],
    [
        'name' => 'Roman',
        'age' => 36,
    ]
);
```

В HTTP body будет отправлено:

```json
{
    "name": "Roman",
    "age": 36
}
```

### PUT

```php
$response = Http::put(
    'https://httpbin.org/put',
    [
        'Accept: application/json',
        'Content-Type: application/json',
    ],
    [
        'name' => 'Roman',
    ]
);
```

### PATCH

```php
$response = Http::patch(
    'https://httpbin.org/patch',
    [
        'Accept: application/json',
        'Content-Type: application/json',
    ],
    [
        'name' => 'Roman',
    ]
);
```

### DELETE

Query parameters передаются третьим аргументом:

```php
$response = Http::delete(
    'https://httpbin.org/delete',
    [
        'Accept: application/json',
    ],
    [
        'id' => 123,
    ]
);
```

Будет выполнен запрос:

```text
https://httpbin.org/delete?id=123
```

## Response

Все запросы возвращают объект, реализующий:

```php
alf89\HttpClient\Contracts\ResponseInterface
```

### HTTP status code

```php
$statusCode = $response->getStatusCode();
```

Например:

```text
200
```

### Response headers

```php
$headers = $response->getHeaders();
```

Пример:

```php
[
    'date' => 'Tue, 25 Aug 2026 11:52:11 GMT',
    'content-type' => 'application/json',
    'content-length' => '595',
    'server' => 'gunicorn/19.9.0',
]
```

### Response body

```php
$body = $response->getBody();
```

Возвращается строка с телом HTTP-ответа.

Для JSON:

```php
$data = json_decode(
    $response->getBody(),
    true,
    flags: JSON_THROW_ON_ERROR
);
```

## Обработка ошибок

### ConnectionException

Если cURL не смог выполнить запрос, выбрасывается:

```php
alf89\HttpClient\Exceptions\ConnectionException
```

Например, при ошибке DNS:

```php
use alf89\HttpClient\Exceptions\ConnectionException;
use alf89\HttpClient\Facades\Http;

try {
    $response = Http::get(
        'https://httpbin111.org'
    );
} catch (ConnectionException $exception) {
    echo $exception->getMessage();

    echo $exception->getCurlErrorCode();
}
```

`ConnectionException` содержит:

```php
$exception->getMessage();
```

Сообщение об ошибке cURL.

```php
$exception->getCurlErrorCode();
```

Код ошибки cURL.

Пример:

```text
Could not resolve host: httpbin111.org
```

### HTTP ошибки

HTTP-статус `4xx` или `5xx` означает, что сервер ответил.

В текущей версии такие ответы возвращаются как обычный `Response`.

Например:

```php
$response = Http::get(
    'https://httpbin.org/status/404'
);

echo $response->getStatusCode();
```

Результат:

```text
404
```

Таким образом:

```text
Ошибка cURL
    ↓
ConnectionException

HTTP 4xx / 5xx
    ↓
Response
```

### HttpException

Пакет также содержит:

```php
alf89\HttpClient\Exceptions\HttpException
```

Класс предоставляет:

```php
$exception->getStatusCode();
$exception->getBody();
$exception->getMessage();
```

В текущей версии `HttpException` не выбрасывается автоматически при получении HTTP-статуса `4xx` или `5xx`.

## Facade

`Http` является основной и рекомендуемой точкой входа в пакет.

```php
use alf89\HttpClient\Facades\Http;

$response = Http::get(...);

$response = Http::post(...);

$response = Http::put(...);

$response = Http::patch(...);

$response = Http::delete(...);
```

Facade самостоятельно создаёт необходимые внутренние зависимости.

Пользователю пакета не требуется создавать:

```text
HttpClientService
CurlTransport
```

вручную.

Конфигурация доступна через:

```php
Http::config()
```

Например:

```php
Http::config()->timeout = 60;

$response = Http::get(
    'https://httpbin.org/get'
);
```

## Архитектура

Пакет разделён на несколько уровней:

```text
             Config
                ↓
Http Facade → HttpClientService
                ↓
        TransportInterface
                ↓
           CurlTransport
                ↓
             PHP cURL
                ↓
             Response
```

### Contracts

Содержит интерфейсы:

```text
HttpClientInterface
TransportInterface
ResponseInterface
```

`HttpClientInterface` определяет публичные HTTP-методы клиента:

```php
get()
post()
put()
patch()
delete()
```

`TransportInterface` определяет механизм выполнения HTTP-запроса.

`ResponseInterface` определяет интерфейс HTTP-ответа.

### Services

`HttpClientService` содержит логику HTTP-клиента.

Он определяет HTTP-метод и передаёт данные в `TransportInterface`.

Например:

```text
HttpClientService::get()
        ↓
TransportInterface::send('GET', ...)
```

### Transport

`CurlTransport` реализует `TransportInterface` и отвечает непосредственно за работу с PHP cURL.

Он:

* формирует URL с query parameters;
* устанавливает HTTP headers;
* устанавливает HTTP method;
* сериализует body в JSON;
* использует настройки `Config`;
* выполняет cURL-запрос;
* получает HTTP status code;
* получает response headers;
* получает response body;
* выбрасывает `ConnectionException` при ошибке cURL.

### Http

Содержит объект HTTP-ответа:

```text
Response
```

### Facades

Содержит:

```text
Http
```

Facade предоставляет единый публичный интерфейс для работы с HTTP-клиентом.

### Exceptions

Содержит исключения:

```text
ConnectionException
HttpException
```

## Структура проекта

```text
src/
├── Config/
│   └── Config.php
│
├── Contracts/
│   ├── HttpClientInterface.php
│   ├── ResponseInterface.php
│   └── TransportInterface.php
│
├── Exceptions/
│   ├── ConnectionException.php
│   └── HttpException.php
│
├── Facades/
│   └── Http.php
│
├── Http/
│   └── Response.php
│
├── Services/
│   └── HttpClientService.php
│
└── Transport/
    └── CurlTransport.php
```

## Дизайн

HTTP-клиент не зависит непосредственно от `CurlTransport`.

Зависимость построена через:

```php
TransportInterface
```

Благодаря этому transport можно заменить другой реализацией:

```text
TransportInterface
       │
       └── CurlTransport
```

Это позволяет в дальнейшем добавлять новые реализации transport без изменения основной логики клиента.

## Текущие ограничения

В текущей версии:

* используется PHP cURL;
* request body отправляется в JSON;
* `Content-Type: application/json` передаётся пользователем;
* GET и DELETE используют query parameters;
* POST, PUT и PATCH используют JSON body;
* HTTP `4xx/5xx` возвращаются как `Response`;
* `ConnectionException` используется для ошибок cURL;
* `HttpException` пока не выбрасывается автоматически;
* поддерживается один основной transport — `CurlTransport`.

## План развития

Развитие проекта:

* улучшение обработки JSON;
* расширенная обработка HTTP-ошибок;
* расширенная работа с HTTP headers;
* корректное формирование URL и query parameters;
* redirect;
* retry;
* PHPUnit-тесты;
* PHPStan;
* middleware;
* logging;
* дополнительные transport;
* поддержка PSR;
* поддержка различных форматов тела запроса.
* добавление новых настроек конфигурации

---

## Лицензия

Пакет распространяется под лицензией MIT.

---

## Автор

**Литвиненко Роман Александрович**

* GitHub: [alf07](https://github.com/alf07/)
* Email: [roma.alf89@gmail.com](mailto:roma.alf89@gmail.com)
* Telegram: @Alf88
* Канал в MAX https://max.ru/channel_alf_dev
