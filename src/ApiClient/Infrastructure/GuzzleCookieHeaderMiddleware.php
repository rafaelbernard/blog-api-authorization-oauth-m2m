<?php

namespace ApiClient\Infrastructure;

use Psr\Http\Message\RequestInterface;

/**
 * Class GuzzleCookieHeaderMiddleware
 *
 * Add a header from a cookie content
 */
class GuzzleCookieHeaderMiddleware
{
    /**
     * @var string
     */
    private $cookieHeaderName;

    /**
     * @var string
     */
    private $cookieName;

    /**
     * @param string $cookieHeaderName
     * @param string $cookieName
     */
    public function __construct($cookieHeaderName, $cookieName)
    {
        $this->cookieHeaderName = $cookieHeaderName;
        $this->cookieName = $cookieName;
    }

    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            return $handler(
                $request->withHeader($this->cookieHeaderName, $this->generateCookieHeader()),
                $options
            );
        };
    }

    private function generateCookieHeader()
    {
        $content = isset($_COOKIE[$this->cookieName]) ? $_COOKIE[$this->cookieName] : '';

        return "{$this->cookieName}={$content};";
    }
}
