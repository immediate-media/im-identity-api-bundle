<?php

declare(strict_types=1);

namespace IM\Fabric\Bundle\IdentityApiBundle\Api;

use IM\Fabric\Bundle\IdentityApiBundle\Client\AccessClient;

class AccessApi extends AbstractApi
{
    public function __construct(
        AccessClient $client,
        protected readonly string $clientId,
        protected readonly string $clientSecret
    ) {
        parent::__construct($client);
    }
}
