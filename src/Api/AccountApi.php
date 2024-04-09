<?php

declare(strict_types=1);

namespace IM\Fabric\Bundle\IdentityApiBundle\Api;

use IM\Fabric\Bundle\IdentityApiBundle\Client\AccountClient;

class AccountApi extends AbstractApi
{
    public function __construct(AccountClient $client)
    {
        parent::__construct($client);
    }
}
