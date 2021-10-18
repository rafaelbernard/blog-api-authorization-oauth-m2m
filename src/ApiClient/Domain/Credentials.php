<?php

namespace ApiClient\Domain;

class Credentials
{
    const CLIENT_CREDENTIALS = 'client_credentials';

    private string $clientId;

    private string $clientSecret;

    private string $audience;

    private string $grantType;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $audience,
        string $grantType = self::CLIENT_CREDENTIALS
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->audience = $audience;
        $this->grantType = $grantType;
    }

    public function clientId(): string
    {
        return $this->clientId;
    }

    public function clientSecret(): string
    {
        return $this->clientSecret;
    }

    public function audience(): string
    {
        return $this->audience;
    }

    public function grantType(): string
    {
        return $this->grantType;
    }
}
