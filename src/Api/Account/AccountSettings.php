<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle\Api\Account;

use IM\Fabric\Package\IdentityApiBundle\Api\AccountApi;

class AccountSettings extends AccountApi
{
    const PATH = '/v1/settings';

    public function getUserSettings(string $tenantId, string $userId): array
    {
        return $this->get(sprintf(self::PATH . '/%s/%s/', $tenantId, $userId));
    }
}
