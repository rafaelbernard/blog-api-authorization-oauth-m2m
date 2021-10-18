<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ApiClient\Application\ApiClientFactory;
use ApiClient\Domain\ClientConfig;
use ApiClient\Domain\Credentials;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;

$clientId = 'id';
$clientSecret = 'secret';
$audience = 'audience';
$baseUri = 'base';
$authBaseUri = 'auth';

$memcache = new \Memcached();
$memcache->addServer('127.0.0.1', '11211');

$cacheStorage = new MemcachedAdapter($memcache);

$credentials = new Credentials($clientId, $clientSecret, $audience);
$clientConfig = new ClientConfig($baseUri, $authBaseUri, $credentials);

$client = ApiClientFactory::create($clientConfig, $cacheStorage);

var_dump($client);
