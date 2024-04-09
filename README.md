# im-identity-api-bundle
Package for interacting with the Identity APIs

## Installation
This bundle needs to be added to your `composer.json` files as follows

```json
"repositories": [
    "immediate/identity-api-bundle": {
        "type": "vcs",
        "url": "https://github.com/immediate-media/im-identity-api-bundle.git"
    },
]
```

It will also need to be added as a dependency

```json
"require": {
  "immediate/im-identity-api-bundle": "^1.0",
}
```

The below should automatically be added to your `config/bundles.php` file as part of a `composer update`. 

```php
IM\Fabric\Package\IdentityApiBundle\IdentityApiBundle::class => ['all' => true]
```

If not, run `composer recipes:install immediate/im-identity-api-bundle --force`

Alongside the package installation, you will also need to require packages that include implementations for PSR 7, PSR 17 and PSR 18 interfaces.

## Configuration

As part of the autoconfiguration, you will need to pass through what env your application is running in. The allowed options are:
- dev
- local
- build
- staging
- preproduction
- production

This will automatically configure the urls for the Identity APIs. For staging, preproduction and production, the urls will be set to their respective envs. 

For dev, local and build, the urls will be set to:
- dev: preproduction env
- local: preproduction env
- build: staging env

Depending on which Identity API you are going to make requests to, you will need to pass through a client id and client secret that has access to the required scopes.

### Service configuration

By default, the bundle will automatically read the required configuration from the ENVIRONMENT variables using the following keys:

- APP_ENV
- IDENTITY_CLIENT
- IDENTITY_CLIENT_SECRET

If you would like to override this, you can add the following to your `services.yaml` file:

```yaml
    IM\Fabric\Package\IdentityApiBundle\Options:
      arguments:
        $options:
          {
            environment: 'yourEnv',
            identityClient: 'SetWithClientId',
            identityClientSecret: 'SetWithClientSecret'
          }
```

## Usage

The bundle works by provides a client class which talks to a specific api, an `apiCall` method which references a specific sub-section of the api and then finally the request to make. 

For example, I have an api that looks like this

```
MyExampleApi

    EntityOne
        - GET /v1/entity-one
        - POST /v1/entity-one
        - GET /v1/entity-one/{id}
        - PUT /v1/entity-one/{id}
        - DELETE /v1/entity-one/{id}
    EntityTwo
        - post /v1/entity-two
        - delete /v1/entity-two/{id}
```

If I wanted to communicate with the `EntityOne` or `EntityTwo` endpoints, I would do the following:

```php
public function __construct(
    private readonly ExampleApiClient $exampleApiClient
)
{
}

public function getEntityOne(): void
{
    return $this->exampleApiClient->apiCall('entityOne')->getEntity();
}

public function deleteEntityTwo(int $id): void
{
    return $this->exampleApiClient->apiCall('entityTwo')->deleteEntity($id);
}
```

The purpose for this is to allow our code to match the api structure and to make it easier to understand what is being called.

The full list of apis and calls that are available are documented [here](documentation/SupportedApiCalls.md)
