# Access API
Use the `AccessClient` to make requests to the Identity Access API.
### Service Token - Get Token
```php
$accessClient->apiCall('serviceToken')->getToken(string $spaceDelimintedScopes);
```

# Account API
Use the `AccountClient` to make requests to the [Identity Account API](https://account-api-production.headless.imdserve.com/docs/index.html).

### Account Settings - Get User Settings
```php
$accountClient->apiCall('accountSettings')->getUserSettings(string $tenant, string $userId);
```
