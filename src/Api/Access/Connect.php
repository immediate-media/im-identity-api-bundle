<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle\Api\Access;

use IM\Fabric\Package\IdentityApiBundle\Api\AccessApi;

class Connect extends AccessApi
{
    private const path = '/connect/token';

    public function getToken(string $scopes): array
    {
        return $this->post(
            self::path,
            [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => $scopes,
            ],
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        );
    }
}
