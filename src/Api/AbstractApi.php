<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle\Api;

use IM\Fabric\Package\IdentityApiBundle\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class AbstractApi implements ApiInterface
{
    public function __construct(protected readonly ClientInterface $client)
    {
    }

    protected function get(string $path) : array
    {
        return $this->parseResponse($this->client->getHttpClient()->get($path));
    }

    protected function post($path, array $parameters = [], array $requestHeaders = []) : array
    {
        return $this->postRaw($path, json_encode($parameters), $requestHeaders);
    }

    protected function postRaw($path, $body, array $requestHeaders = []) : array
    {
        return $this->parseResponse($this->client->getHttpClient()->post(
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
