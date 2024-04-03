<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle\Client;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use IM\Fabric\Package\IdentityApiBundle\Api\Account\AccountSettings;
use IM\Fabric\Package\IdentityApiBundle\Api\ApiInterface;
use IM\Fabric\Package\IdentityApiBundle\Builder\ClientBuilder;
use IM\Fabric\Package\IdentityApiBundle\Options;
use InvalidArgumentException;

class AccountClient implements ClientInterface
{
    private ClientBuilder $clientBuilder;

    public function __construct(Options $options, private readonly AccessClient $accessClient)
    {
        $this->clientBuilder = $options->getClientBuilder();
        $this->clientBuilder->addPlugin(new BaseUriPlugin($options->getAccountsEndpoint()));
        $this->clientBuilder->addPlugin(new HeaderDefaultsPlugin([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->getAuthHeaders()
        ]));
    }

    public function apiCall(string $name): ApiInterface
    {
        return match ($name) {
            'accountSettings' => new AccountSettings($this),
            default => throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name)),
        };
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->clientBuilder->getHttpClient();
    }

    private function getAuthHeaders(): string
    {
        $body = $this->accessClient->apiCall('accessToken')->getToken('IdentityAccountApi');
        return 'Bearer ' . $body['access_token'];
    }
}
