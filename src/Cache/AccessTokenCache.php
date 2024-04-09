<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle\Cache;

use IM\Fabric\Package\IdentityApiBundle\Client\AccessClient;
use RuntimeException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class AccessTokenCache
{
    private const CACHE_KEY_FORMAT = 'identity-access-%s';

    public function __construct(
        private readonly CacheInterface $cache,
        private readonly AccessClient $accessClient
    ) {
    }

    public function getToken(string $scopes): string
    {
        return $this->cache->get($this->getCacheKey($scopes), function (ItemInterface $item) use ($scopes) {
            try {
                $token = $this->accessClient->apiCall('serviceToken')->getToken('IdentityAccountApi');
            } catch (RuntimeException $e) {
                throw new RuntimeException('Could not retrieve token from Identity Access API.', 0, $e);
            }

            if (!isset($token['access_token'], $token['expires_in'])) {
                throw new RuntimeException('Count not retrieve token from Identity Access API.');
            }

            $item->expiresAfter((int)($token['expires_in'] * 0.9)); // 90% of the token's expiry time
            return $token['access_token'];
        });
    }

    private function getCacheKey(string $scopes): string
    {
        return sprintf(self::CACHE_KEY_FORMAT, $scopes);
    }
}
