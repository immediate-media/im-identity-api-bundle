<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle\Client;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use IM\Fabric\Package\IdentityApiBundle\Api\Access\Connect;
use IM\Fabric\Package\IdentityApiBundle\Api\ApiInterface;
use IM\Fabric\Package\IdentityApiBundle\Builder\ClientBuilder;
use IM\Fabric\Package\IdentityApiBundle\Options;
use InvalidArgumentException;

class AccessClient implements ClientInterface
{
    private ClientBuilder $clientBuilder;
    private readonly string $clientId;
    private readonly string $clientSecret;

    public function __construct(Options $options)
    {
        $this->clientBuilder = $options->getClientBuilder();
        $this->clientBuilder->addPlugin(new BaseUriPlugin($options->getAccessEndpoint()));

        $this->clientId = $options->getIdentityClient();
        $this->clientSecret = $options->getIdentityClientSecret();
    }

    public function apiCall(string $name): ApiInterface
    {
        return match ($name) {
            'accessToken' => new Connect(
                $this,
                $this->clientId,
                $this->clientSecret
            ),
            default => throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name)),
        };
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->clientBuilder->getHttpClient();
    }

}
