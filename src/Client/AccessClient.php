<?php

declare(strict_types=1);

namespace IM\Fabric\Bundle\IdentityApiBundle\Client;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use IM\Fabric\Bundle\IdentityApiBundle\Api\Access\Connect;
use IM\Fabric\Bundle\IdentityApiBundle\Api\ApiInterface;
use IM\Fabric\Bundle\IdentityApiBundle\Builder\ClientBuilder;
use IM\Fabric\Bundle\IdentityApiBundle\Options;
use InvalidArgumentException;

class AccessClient implements ClientInterface
{
    public function __construct(private readonly Options $options)
    {
    }

    public function apiCall(string $name): ApiInterface
    {
        return match ($name) {
            'serviceToken' => new Connect(
                $this,
                $this->options->getIdentityClient(),
                $this->options->getIdentityClientSecret()
            ),
            default => throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name)),
        };
    }

    public function prepareHttpClient(): HttpMethodsClientInterface
    {
        $clientBuilder = $this->options->getClientBuilder();
        $clientBuilder->addPlugin(new BaseUriPlugin($this->options->getAccessEndpoint()));
        $clientBuilder->addPlugin(new HeaderDefaultsPlugin([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]));

        return $clientBuilder->getHttpClient();
    }
}
