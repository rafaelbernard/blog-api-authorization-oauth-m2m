<?php

namespace ApiClient\Domain;

class ClientConfig
{
    private string $baseUri;

    private string $authBaseUri;

    private Credentials $credentials;

    public function __construct(string $baseUri, string $authBaseUri, Credentials $credentials)
    {
        $this->baseUri = $baseUri;
        $this->authBaseUri = $authBaseUri;
        $this->credentials = $credentials;
    }

    public function baseUri(): string
    {
        return $this->baseUri;
    }

    public function authBaseUri(): string
    {
        return $this->authBaseUri;
    }

    public function credentials(): Credentials
    {
        return $this->credentials;
    }
}
