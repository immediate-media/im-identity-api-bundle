<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle\Client;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\ContentTypePlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Message\Authentication\Bearer;
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
        ]));
        $this->clientBuilder->addPlugin(new AuthenticationPlugin(new Bearer($this->getToken())));
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

    private function getToken(): string
    {
        $body = $this->accessClient->apiCall('accessToken')->getToken('IdentityAccountApi');
        return $body['access_token'];
    }
}
