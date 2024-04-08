<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle\Api;

use Http\Client\Exception;
use IM\Fabric\Package\IdentityApiBundle\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractApi implements ApiInterface
{
    public function __construct(protected readonly ClientInterface $client)
    {
    }

    /**
     * @throws Exception
     */
    protected function get(string $path): array
    {
        return $this->parseResponse($this->client->prepareHttpClient()->get($path));
    }

    protected function post($path, array $body = [], array $requestHeaders = []): array
    {
        return $this->postRaw($path, json_encode($body), $requestHeaders);
    }

    /**
     * @throws Exception
     */
    protected function postRaw($path, $body, array $requestHeaders = []): array
    {
        return $this->parseResponse($this->client->prepareHttpClient()->post(
            $path,
            $requestHeaders,
            $body
        ));
    }

    private function parseResponse(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
