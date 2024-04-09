<?php

declare(strict_types=1);

namespace IM\Fabric\Bundle\IdentityApiBundle\Api\Access;

use IM\Fabric\Bundle\IdentityApiBundle\Api\AccessApi;

class Connect extends AccessApi
{
    private const PATH = '/connect/token';

    public function getToken(string $scopes): array
    {
        return $this->postRaw(
            self::PATH,
            sprintf(
                'grant_type=client_credentials&client_id=%s&client_secret=%s&scope=%s',
                $this->clientId,
                $this->clientSecret,
                $scopes
            )
        );
    }
}
