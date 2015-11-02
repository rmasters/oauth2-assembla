# Assembla provider for League OAuth 2 Client

This package provides a simple Assembla provider for [The PHP League OAuth2
Client][oauth2client].

## Usage

```
composer require rmasters/oauth2-assembla
```

```php
$provider = new Assembla([
  'clientId' => getenv('ASSEMBLA_CLIENT_ID'),
  'clientSecret' => getenv('ASSEMBLA_CLIENT_SECRET'),
  'redirectUri' => getenv('ASSEMBLA_REDIRECT_URI'),
]);

// Send to Assembla for authorization
if (!isset($_GET['code'])) {
    header('Location: ' . $provider->getAuthorizationUrl());
    exit;
}

// Get an access token from an authorization code
$token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
$_SESSION['assembla'] = $token;

// Get the authenticated user
$user = $provider->getResourceOwner($token);
assert($user instanceof AssemblaResourceOwner);
printf("Logged in as %s", $user->getName());
```

# License

[MIT Licensed](/LICENSE)

[oauth2client]: http://oauth2-client.thephpleague.com
