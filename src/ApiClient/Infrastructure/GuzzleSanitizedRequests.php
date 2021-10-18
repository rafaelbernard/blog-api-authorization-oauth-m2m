<?php

namespace ApiClient\Infrastructure;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use ApiClient\Domain\ClientErrorException;

trait GuzzleSanitizedRequests
{
    /**
     * @param $uri
     * @return mixed
     * @throws ClientErrorException
     */
    private function get($uri)
    {
        return $this->request('GET', $uri);
    }

    /**
     * @param string $uri
     * @param mixed $payload
     * @return void
     */
    private function post($uri, $payload = [])
    {
        $this->request('POST', $uri, ['json' => $payload]);
    }

    /**
     * @param string $uri
     * @param mixed $payload
     * @return void
     */
    private function patch($uri, $payload = [])
    {
        $this->request('PATCH', $uri, ['json' => $payload]);
    }

    /**
     * @param string $uri
     * @param mixed $payload
     * @return void
     */
    private function put($uri, $payload = [])
    {
        $this->request('PUT', $uri, ['json' => $payload]);
    }

    /**
     * @param string $uri
     * @param mixed $payload
     * @return void
     */
    private function delete($uri, $payload = [])
    {
        $this->request('DELETE', $uri, ['json' => $payload]);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed
     * @throws ClientErrorException|\Exception
     */
    private function request($method, $uri = '', array $options = [])
    {
        try {
            $response = $this->client()->request($method, $uri, $options);
            return json_decode((string) $response->getBody(), true); // associative=true
        } catch (BadResponseException $e) {
            throw $this->sanitizedException($e);
        }
    }

    /**
     * Sanitize before throwing an exception to the user
     *
     * @param BadResponseException $e
     * @return ClientErrorException
     */
    protected function sanitizedException(BadResponseException $e)
    {
        // 500 errors are being used for bad input and validation errors - overwrite if needed
        if ($e->getResponse() && $e->getResponse()->getStatusCode() === 500) {
            return new ClientErrorException($e->getResponse()->getBody(), $e->getResponse()->getStatusCode());
        }

        $this->logException($e);

        return new ClientErrorException('Client error exception.');
    }

    protected function logException(\Exception $e)
    {
        error_log(sprintf('Client error exception: Message: %s | Trace: %s', $e->getMessage(), $e->getTraceAsString()));
    }

    /**
     * @return ClientInterface
     */
    abstract protected function client();
}
