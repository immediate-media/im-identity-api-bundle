<?php

namespace IM\Fabric\Bundle\IdentityApiBundle\Test\Client;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use IM\Fabric\Bundle\IdentityApiBundle\Api\Access\Connect;
use IM\Fabric\Bundle\IdentityApiBundle\Api\Account\AccountSettings;
use IM\Fabric\Bundle\IdentityApiBundle\Builder\ClientBuilder;
use IM\Fabric\Bundle\IdentityApiBundle\Cache\AccessTokenCache;
use IM\Fabric\Bundle\IdentityApiBundle\Client\AccountClient;
use IM\Fabric\Bundle\IdentityApiBundle\Options;
use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

class AccountClientTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private AccountClient $unit;
    private Options $options;
    private AccessTokenCache $tokenCache;

    public function setUp(): void
    {
        $this->options = Mockery::mock(Options::class);
        $this->tokenCache = Mockery::mock(AccessTokenCache::class);

        $this->unit = new AccountClient($this->options, $this->tokenCache);
    }

    public function testApiCall()
    {
        $this->assertInstanceOf(
            AccountSettings::class,
            $this->unit->apiCall('accountSettings')
        );

        $this->expectException(InvalidArgumentException::class);
        $this->unit->apiCall('undefined');
    }

    public function testPrepareHttpClient()
    {
        $clientBuilder = Mockery::mock(ClientBuilder::class);
        $clientBuilder->expects('getHttpClient')->andReturn(Mockery::mock(HttpMethodsClientInterface::class));
        $clientBuilder->expects('addPlugin')->with(Mockery::type(BaseUriPlugin::class));
        $clientBuilder->expects('addPlugin')->with(Mockery::type(HeaderDefaultsPlugin::class));
        $clientBuilder->expects('addPlugin')->with(Mockery::type(AuthenticationPlugin::class));
        $this->options->expects('getClientBuilder')->andReturn($clientBuilder);

        $this->tokenCache->expects('getToken')->with('IdentityAccountApi')->andReturn('token');

        $uriInterface = Mockery::mock(UriInterface::class);
        $uriInterface->allows('getHost')->andReturn('host');
        $uriInterface->allows('getPath')->andReturn('path');
        $this->options->expects('getAccountsEndpoint')->andReturn($uriInterface);

        $this->unit->prepareHttpClient();
    }
}
