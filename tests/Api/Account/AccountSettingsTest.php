<?php

namespace IM\Fabric\Bundle\IdentityApiBundle\Test\Api\Account;

use Http\Client\Common\HttpMethodsClientInterface;
use IM\Fabric\Bundle\IdentityApiBundle\Api\Account\AccountSettings;
use IM\Fabric\Bundle\IdentityApiBundle\Client\AccountClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class AccountSettingsTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private AccountSettings $unit;
    private AccountClient $client;

    public function setUp(): void
    {
        $this->client = Mockery::mock(AccountClient::class);
        $this->unit = new AccountSettings($this->client);
    }

    public function testGetUserSettings()
    {
        $tenantId = 'tenantId';
        $userId = 'userId';
        $response = ['response'];

        $clientInterface = Mockery::mock(HttpMethodsClientInterface::class);
        $responseInterface = Mockery::mock(ResponseInterface::class);

        $responseInterface->expects('getStatusCode')->andReturn(200);
        $responseInterface->expects('getBody')->andReturnSelf();
        $responseInterface->expects('getContents')->andReturn(json_encode($response));

        $this->client->expects('prepareHttpClient')->andReturn($clientInterface);

        $clientInterface->expects('get')
            ->with('/v1/settings/' . $tenantId . '/' . $userId . '/')
            ->andReturn($responseInterface);

        $this->assertEquals($response, $this->unit->getUserSettings($tenantId, $userId));
    }
}
