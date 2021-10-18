<?php

namespace ApiClient\Application;

use ApiClient\Domain\ClientConfig;
use ApiClient\Infrastructure\GuzzleAuth0M2MTokenMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Symfony\Contracts\Cache\CacheInterface;

class ApiClientFactory
{
    public static function create(ClientConfig $clientConfig, CacheInterface $cacheStorage): Client
    {
        $stack = HandlerStack::create();

        $handler = new GuzzleAuth0M2MTokenMiddleware(
            new Client(['base_uri' => $clientConfig->authBaseUri()]),
            $clientConfig->credentials(),
            $cacheStorage
        );
        $stack->push($handler);

        return new Client([
            'base_uri' => $clientConfig->baseUri(),
            'handler' => $stack
        ]);
    }
}
