<?php

namespace IM\Fabric\Bundle\IdentityApiBundle\Test\Cache;

use IM\Fabric\Bundle\IdentityApiBundle\Cache\AccessTokenCache;
use IM\Fabric\Bundle\IdentityApiBundle\Client\AccessClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class AccessTokenCacheTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private AccessTokenCache $unit;
    private CacheInterface $cacheInterface;
    private AccessClient $accessClient;

    public function setUp(): void
    {
        $this->cacheInterface = Mockery::mock(CacheInterface::class);
        $this->accessClient = Mockery::mock(AccessClient::class);

        $this->unit = new AccessTokenCache($this->cacheInterface, $this->accessClient);
    }

    public function testGetTokenReturnsCachedData(): void
    {
        $scopes = 'scopes';
        $token = 'test';

        $this->cacheInterface->expects('get')
            ->with('identity-access-scopes', Mockery::type('callable'))
            ->andReturn($token);

        $this->assertEquals($token, $this->unit->getToken($scopes));
    }
}
