<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle\Api;

use IM\Fabric\Package\IdentityApiBundle\Client\AccountClient;

class AccountApi extends AbstractApi
{
    public function __construct(AccountClient $client)
    {
        parent::__construct($client);
    }
}
