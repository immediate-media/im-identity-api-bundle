<?php

declare(strict_types=1);

namespace IM\Fabric\Bundle\IdentityApiBundle\Client;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Message\Authentication\Bearer;
use IM\Fabric\Bundle\IdentityApiBundle\Api\Account\AccountSettings;
use IM\Fabric\Bundle\IdentityApiBundle\Api\ApiInterface;
use IM\Fabric\Bundle\IdentityApiBundle\Cache\AccessTokenCache;
use IM\Fabric\Bundle\IdentityApiBundle\Options;
use InvalidArgumentException;

class AccountClient implements ClientInterface
{
    public function __construct(private readonly Options $options, private readonly AccessTokenCache $accessTokenCache)
    {
    }

    public function apiCall(string $name): ApiInterface
    {
        return match ($name) {
            'accountSettings' => new AccountSettings($this),
            default => throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name)),
        };
    }

    public function prepareHttpClient(): HttpMethodsClientInterface
    {
        $clientBuilder = $this->options->getClientBuilder();
        $clientBuilder->addPlugin(new BaseUriPlugin($this->options->getAccountsEndpoint()));
        $clientBuilder->addPlugin(new HeaderDefaultsPlugin([
            'Content-Type' => 'application/json',
        ]));
        $clientBuilder->addPlugin(
            new AuthenticationPlugin(new Bearer($this->accessTokenCache->getToken('IdentityAccountApi')))
        );

        return $clientBuilder->getHttpClient();
    }
}
