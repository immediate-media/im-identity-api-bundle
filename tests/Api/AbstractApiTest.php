<?php

namespace IM\Fabric\Bundle\IdentityApiBundle\Test\Api;

use Http\Client\Common\HttpMethodsClientInterface;
use IM\Fabric\Bundle\IdentityApiBundle\Api\AbstractApi;
use IM\Fabric\Bundle\IdentityApiBundle\Client\ClientInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class AbstractApiTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const DEFAULT_RESPONSE_BODY = ['response'];
    private AbstractApi $unit;
    private HttpMethodsClientInterface $httpClientsMethods;
    private ResponseInterface $responseInterface;

    public function setUp(): void
    {
        $this->httpClientsMethods = Mockery::mock(HttpMethodsClientInterface::class);

        $client = Mockery::mock(ClientInterface::class);
        $client->allows('prepareHttpClient')->andReturn($this->httpClientsMethods)->byDefault();

        $this->responseInterface = Mockery::mock(ResponseInterface::class);
        $this->responseInterface->allows('getStatusCode')->andReturn(200)->byDefault();
        $this->responseInterface->allows('getBody')->andReturnSelf()->byDefault();
        $this->responseInterface->allows('getContents')
            ->andReturn(json_encode(self::DEFAULT_RESPONSE_BODY))
            ->byDefault();

        $this->unit = new class ($client) extends AbstractApi {
            public function get($path): array
            {
                return parent::get($path);
            }

            public function post($path, array $body = [], array $requestHeaders = []): array
            {
                return parent::post($path, $body, $requestHeaders);
            }

            public function postRaw($path, $body, array $requestHeaders = []): array
            {
                return parent::postRaw($path, $body, $requestHeaders);
            }
        };
    }

    public function testGet(): void
    {
        $this->httpClientsMethods->expects('get')->with('/')->andReturn($this->responseInterface);
        $this->assertSame(self::DEFAULT_RESPONSE_BODY, $this->unit->get('/'));
    }

    public function testPost(): void
    {
        $this->httpClientsMethods->expects('post')
            ->with('/', [], json_encode(['body']))
            ->andReturn($this->responseInterface);
        $this->assertSame(self::DEFAULT_RESPONSE_BODY, $this->unit->post('/', ['body']));
    }

    public function testPostRaw(): void
    {
        $this->httpClientsMethods->expects('post')
            ->with('/', [], 'whatever POST body')
            ->andReturn($this->responseInterface);
        $this->assertSame(self::DEFAULT_RESPONSE_BODY, $this->unit->postRaw('/', 'whatever POST body'));
    }

    public function testGetThrowsExceptionOn4xxResponseCode(): void
    {
        $this->httpClientsMethods->expects('get')->with('/')->andReturn($this->responseInterface);
        $this->responseInterface->expects('getStatusCode')->andReturn(404);
        $this->expectException(RuntimeException::class);
        $this->unit->get('/');
    }
}
