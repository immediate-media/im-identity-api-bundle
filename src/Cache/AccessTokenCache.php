<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle\Cache;

use IM\Fabric\Package\IdentityApiBundle\Client\AccessClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class AccessTokenCache
{
    private const CACHE_KEY_FORMAT = 'identity-access-%s';

    public function __construct(
        private CacheInterface $cache,
        private AccessClient $accessClient
    ) {
    }

    public function getToken(string $scopes): string
    {
        return $this->cache->get($this->getCacheKey($scopes), function (ItemInterface $item) use ($scopes) {
            $token = $this->accessClient->apiCall('serviceToken')->getToken('IdentityAccountApi');
            $item->expiresAfter($token['expires_in'] * 0.9); // 90% of the token's expiry time
            return $token['access_token'];
        });
    }

    private function getCacheKey(string $scopes): string
    {
        return sprintf(self::CACHE_KEY_FORMAT, $scopes);
    }
}
