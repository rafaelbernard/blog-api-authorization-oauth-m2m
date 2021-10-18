<?php

namespace ApiClient\Infrastructure;

use ApiClient\Domain\Credentials;
use GuzzleHttp\ClientInterface;
use JetBrains\PhpStorm\Pure;
use Psr\Http\Message\RequestInterface;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * Class GuzzleAuth0M2MTokenMiddleware
 *
 * Middleware to handle Auth0 machine-to-machine token generation
 */
class GuzzleAuth0M2MTokenMiddleware
{
    private ClientInterface $client;

    private Credentials $credentials;

    private CacheInterface $cacheService;

    public function __construct(ClientInterface $client, Credentials $credentials, CacheInterface $cacheStorage)
    {
        $this->client = $client;
        $this->credentials = $credentials;
        $this->cacheService = $cacheStorage;
    }

    public function __invoke(callable $handler): \Closure
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $token = $this->getToken();
            $request = $request->withHeader('Authorization', "{$token['token_type']} {$token['access_token']}");
            return $handler($request, $options);
        };
    }

    private function getToken()
    {
        return $this->cacheService->get($this->accessTokenCacheKey(), $this->generateToken());
    }

    private function generateToken()
    {
        $response = $this->client->post('', ['json' => [
            'grant_type' => $this->credentials->grantType(),
            'client_id' => $this->credentials->clientId(),
            'client_secret' => $this->credentials->clientSecret(),
            'audience' => $this->credentials->audience(),
        ]]);

        return json_decode((string) $response->getBody(), $assoc = true);
    }

    #[Pure]
    private function accessTokenCacheKey(): string
    {
        return "{$this->credentials->audience()}:{$this->credentials->clientId()}";
    }
}
