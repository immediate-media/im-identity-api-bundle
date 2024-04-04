<?php

declare(strict_types=1);

namespace IM\Fabric\Package\IdentityApiBundle;

use Http\Discovery\Psr17FactoryDiscovery;
use IM\Fabric\Package\IdentityApiBundle\Builder\ClientBuilder;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Options
{
    private const SUPPORTED_ENVS = ['local', 'build', 'staging', 'preproduction', 'production'];
    private const IDENTITY_ACCOUNTS_ENDPOINT_DICTIONARY = [
        'local' => 'https://account-api-preproduction.headless-preproduction.imdserve.com',
        'build' => 'https://account-api-staging.headless-sandbox.imdserve.com',
        'staging' => 'https://account-api-staging.headless-sandbox.imdserve.com',
        'preproduction' => 'https://account-api-preproduction.headless-preproduction.imdserve.com',
        'production' => 'https://account-api-production.headless.imdserve.com'
    ];
    private const IDENTITY_ACCESS_ENDPOINT_DICTIONARY = [
        'local' => 'https://access-api.preproduction-api.immediate.co.uk',
        'build' => 'https://access-api.staging-api.immediate.co.uk',
        'staging' => 'https://access-api.staging-api.immediate.co.uk',
        'preproduction' => 'https://access-api.preproduction-api.immediate.co.uk',
        'production' => 'https://access-api.api.immediate.co.uk'
    ];
    private array $options;
    private UriFactoryInterface $uriFactory;

    public function __construct(
        array $options = [],
        UriFactoryInterface $uriFactory = null
    )
    {
        $this->uriFactory = $uriFactory ?: Psr17FactoryDiscovery::findUriFactory();
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function getClientBuilder(): ClientBuilder
    {
        return new ClientBuilder();
    }

    public function getIdentityClient(): string
    {
        return $this->options['identityClient'];
    }

    public function getIdentityClientSecret(): string
    {
        return $this->options['identityClientSecret'];
    }

    public function getEnvironment(): string
    {
        return $this->options['environment'];
    }

    public function getAccountsEndpoint(): UriInterface
    {
        return $this->uriFactory->createUri(self::IDENTITY_ACCOUNTS_ENDPOINT_DICTIONARY[$this->getEnvironment()]);
    }

    public function getAccessEndpoint(): UriInterface
    {
        return $this->uriFactory->createUri(self::IDENTITY_ACCESS_ENDPOINT_DICTIONARY[$this->getEnvironment()]);
    }

    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'environment' => 'preproduction',
        ]);

        $resolver->setAllowedValues('environment', self::SUPPORTED_ENVS);
        $resolver->setRequired(['identityClient', 'identityClientSecret']);
    }
}
