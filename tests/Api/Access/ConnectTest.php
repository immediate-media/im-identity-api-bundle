<?php

namespace IM\Fabric\Bundle\IdentityApiBundle\Test\Api\Access;

use Http\Client\Common\HttpMethodsClientInterface;
use IM\Fabric\Bundle\IdentityApiBundle\Api\Access\Connect;
use IM\Fabric\Bundle\IdentityApiBundle\Client\AccessClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ConnectTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const DEFAULT_CLIENT = 'client';
    private const DEFAULT_SECRET = 'secret';
    private Connect $unit;
    private AccessClient $client;

    public function setUp(): void
    {
        $this->client = Mockery::mock(AccessClient::class);
        $this->unit = new Connect(
            $this->client,
            self::DEFAULT_CLIENT,
            self::DEFAULT_SECRET
        );
    }

    public function testGetToken(): void
    {
        $expected = ['token' => 'token'];

        $clientInterface = Mockery::mock(HttpMethodsClientInterface::class);
        $responseInterface = Mockery::mock(ResponseInterface::class);
        $responseInterface->expects('getBody')->andReturnSelf();
        $responseInterface->expects('getContents')->andReturn(json_encode($expected));

        $this->client->expects('prepareHttpClient')->andReturn($clientInterface);
        $clientInterface->expects('post')->with(
            '/connect/token',
            [],
            'grant_type=client_credentials&client_id=' . self::DEFAULT_CLIENT
            . '&client_secret=' . self::DEFAULT_SECRET . '&scope=scope'
        )->andReturn($responseInterface);

        $this->assertEquals($expected, $this->unit->getToken('scope'));
    }
}
