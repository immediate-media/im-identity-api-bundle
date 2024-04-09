<?php

declare(strict_types=1);

namespace IM\Fabric\Bundle\IdentityApiBundle\Api\Account;

use IM\Fabric\Bundle\IdentityApiBundle\Api\AccountApi;

class AccountSettings extends AccountApi
{
    private const PATH = '/v1/settings';

    public function getUserSettings(string $tenantId, string $userId): array
    {
        return $this->get(sprintf(self::PATH . '/%s/%s/', $tenantId, $userId));
    }
}
