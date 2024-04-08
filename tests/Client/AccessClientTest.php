<?php

namespace IM\Fabric\Package\IdentityApiBundle\Test\Client;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use IM\Fabric\Package\IdentityApiBundle\Api\Access\Connect;
use IM\Fabric\Package\IdentityApiBundle\Builder\ClientBuilder;
use IM\Fabric\Package\IdentityApiBundle\Client\AccessClient;
use IM\Fabric\Package\IdentityApiBundle\Options;
use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

class AccessClientTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private AccessClient $unit;
    private Options $options;

    public function setUp(): void
    {
        $this->options = Mockery::mock(Options::class);

        $this->unit = new AccessClient($this->options);
    }

    public function testApiCall()
    {
        $this->options->expects('getIdentityClient')->andReturn('client_id');
        $this->options->expects('getIdentityClientSecret')->andReturn('client_secret');
        $this->assertInstanceOf(
            Connect::class,
            $this->unit->apiCall('serviceToken')
        );

        $this->expectException(InvalidArgumentException::class);
        $this->unit->apiCall('undefined');
    }

    public function testPrepareHttpClient()
    {
        $clientBuilder = Mockery::mock(ClientBuilder::class);
        $clientBuilder->expects('getHttpClient')->andReturn(Mockery::mock(HttpMethodsClientInterface::class));

        $this->options->expects('getClientBuilder')->andReturn($clientBuilder);

        $uriInterface = Mockery::mock(UriInterface::class);
        $uriInterface->allows('getHost')->andReturn('host');
        $uriInterface->allows('getPath')->andReturn('path');
        $this->options->expects('getAccessEndpoint')->andReturn($uriInterface);

        $clientBuilder->expects('addPlugin')->with(Mockery::type(BaseUriPlugin::class));
        $clientBuilder->expects('addPlugin')->with(Mockery::type(HeaderDefaultsPlugin::class));

        $this->unit->prepareHttpClient();
    }
}
