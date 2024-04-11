<?php

namespace IM\Fabric\Bundle\IdentityApiBundle\Tests;

use IM\Fabric\Bundle\IdentityApiBundle\Options;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class OptionsTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private Options $unit;

    public function setUp(): void
    {
        $this->unit = new Options([
            'identityClient' => 'client',
            'identityClientSecret' => 'secret'
        ]);
    }

    public function testGetIdentityClient(): void
    {
        $this->assertSame('client', $this->unit->getIdentityClient());
    }

    public function testGetIdentityClientSecret(): void
    {
        $this->assertSame('secret', $this->unit->getIdentityClientSecret());
    }

    #[DataProvider('environmentDataProvider')]
    public function testGetEnvironment(string $env, string $expectedEnv): void
    {
        $unit = $this->buildUnitWithConfiguredEnvironment($env);

        $this->assertSame($expectedEnv, $unit->getEnvironment());
    }

    #[DataProvider('accountUrlProvider')]
    public function testGetAccountsEndpoint(string $env, string $expected): void
    {
        $unit = $this->buildUnitWithConfiguredEnvironment($env);

        $this->assertSame($expected, $this->uriInterfaceToString($unit->getAccountsEndpoint()));
    }

    #[DataProvider('accessUrlProvider')]
    public function testGetAccessEndpoint(string $env, string $expected): void
    {
        $unit = $this->buildUnitWithConfiguredEnvironment($env);

        $this->assertSame($expected, $this->uriInterfaceToString($unit->getAccessEndpoint()));
    }

    #[DataProvider('exceptionErrorProvider')]
    public function testOptionsThrowsExceptionWhenRequiredOptionsMissing(array $optionsArgs, string $message): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage($message);
        new Options($optionsArgs);
    }

    public static function environmentDataProvider(): iterable
    {
        yield 'no env configured' => [
            '',
            'preproduction',
        ];
        yield 'local env' => [
            'local',
            'local'
        ];
        yield 'build env' => [
            'build',
            'build'
        ];
        yield 'staging env' => [
            'staging',
            'staging'
        ];
        yield 'preprod env' => [
            'preproduction',
            'preproduction'
        ];
        yield 'prod env' => [
            'production',
            'production'
        ];
    }

    public static function accountUrlProvider(): iterable
    {
        yield 'no env configured' => [
            '',
            'https://account-api-preproduction.headless-preproduction.imdserve.com'
        ];
        yield 'local env' => [
            'local',
            'https://account-api-preproduction.headless-preproduction.imdserve.com'
        ];
        yield 'build env' => [
            'build',
            'https://account-api-staging.headless-sandbox.imdserve.com'
        ];
        yield 'staging env' => [
            'staging',
            'https://account-api-staging.headless-sandbox.imdserve.com'
        ];
        yield 'prprod env' => [
            'preproduction',
            'https://account-api-preproduction.headless-preproduction.imdserve.com'
        ];
        yield 'prod env' => [
            'production',
            'https://account-api-production.headless.imdserve.com'
        ];
    }

    public static function accessUrlProvider(): iterable
    {
        yield 'no env configured' => [
            '',
            'https://access-api.preproduction-api.immediate.co.uk'
        ];
        yield 'local env' => [
            'local',
            'https://access-api.preproduction-api.immediate.co.uk'
        ];
        yield 'build env' => [
            'build',
            'https://access-api.staging-api.immediate.co.uk'
        ];
        yield 'staging env' => [
            'staging',
            'https://access-api.staging-api.immediate.co.uk'
        ];
        yield 'preprod env' => [
            'preproduction',
            'https://access-api.preproduction-api.immediate.co.uk'
        ];
        yield 'prod env' => [
            'production',
            'https://access-api.api.immediate.co.uk'
        ];
    }

    public static function exceptionErrorProvider(): iterable
    {
        yield 'No options provided' => [
                [],
                'The required options "identityClient", "identityClientSecret" are missing.'
            ];
        yield 'No identity client' => [
                ['identityClientSecret' => 'secret'],
                'The required option "identityClient" is missing.'
            ];
        yield 'No client secret' => [
                ['identityClient' => 'client'],
                'The required option "identityClientSecret" is missing.'
            ];
    }

    private function buildUnitWithConfiguredEnvironment(string $environment): Options
    {
        $options = [
            'identityClient' => 'client',
            'identityClientSecret' => 'secret',
        ];

        if ($environment) {
            $options['environment'] = $environment;
        }

        return new Options($options);
    }

    private function uriInterfaceToString(UriInterface $uri): string
    {
        return $uri->getScheme() . '://' . $uri->getHost();
    }
}
