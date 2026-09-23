# hanzoai/hanzoai

The PHP client for the Hanzo API: every operation the document declares,
generated from the API's own OpenAPI document.

## Install

```bash
composer require hanzoai/hanzoai
```

Packagist serves every `v*` tag of this repository. To work on the client itself,
build it in place:

```bash
git clone https://github.com/hanzoai/php-sdk.git
cd php-sdk
composer install
```

PHP 8.1 or newer, with `curl`, `json` and `mbstring`. Composer brings Guzzle 7.

## Quickstart

```php
<?php
require 'vendor/autoload.php';

use Hanzo\Cloud\Api\AccountApi;
use Hanzo\Cloud\Configuration;

$config = (new Configuration())
    ->setHost('https://api.hanzo.ai')
    ->setAccessToken(getenv('HANZO_API_KEY'));

$keys = (new AccountApi(null, $config))->getAccountKeys();

printf("%d keys on this account\n", count($keys->getKeys() ?? []));
```

Every API class takes `(?ClientInterface $client, ?Configuration $config,
?HeaderSelector $selector, int $hostIndex)`, all four defaulted, so passing
`null` for the first gives you a default Guzzle client. `ClientInterface` is
`GuzzleHttp\ClientInterface`.

## Auth

The document declares one security scheme — `bearer` — and applies it to every
operation that does not opt out, so the credential goes in one place:

```php
$config->setAccessToken($token);
```

Every operation the document secures reads that field and writes
`Authorization: Bearer <token>`; with no token set it sends none, which is how
`examples/models.php` reads the model catalog without a credential. The token is
an IAM access token or an API key — `pk-` publishable, `sk-` secret.

## Untyped responses

Some operations state the address of a route and not the shape of its reply, so
their method returns `void`. Their `<operation>Request()` builder is public, so
the PSR-7 request the client would have sent is one call away and the body is
yours to read:

```php
$http = new GuzzleHttp\Client();
$api  = new Hanzo\Cloud\Api\AiApi($http, $config);

$body = (string) $http->send($api->getModelsRequest())->getBody();
```

## Examples

- `examples/models.php` — the model catalog, with no credential at all.
- `examples/hello.php` — the keys this account holds, which needs `HANZO_API_KEY`.

```bash
php examples/models.php
```

`HANZO_BASE_URL` points both of them somewhere other than `https://api.hanzo.ai`.

## Generated, not written

`src/` is projected from one document at one commit, which `.spec-lock` names by
sha256. Regenerate it with the driver in `hanzoai/openapi` — `OPENAPI` is a
checkout of that repo, `SPEC` the document (needs java and uv):

```bash
OPENAPI=/path/to/openapi SPEC=/path/to/cloud/openapi.yaml ./scripts/generate.sh
OPENAPI=/path/to/openapi SPEC=/path/to/cloud/openapi.yaml ./scripts/generate.sh --check
```

Without `SPEC` the driver fetches the ref `.spec-lock` names and refuses bytes
that hash to anything else, which needs a credential for the forge; CI's client
lane passes both by value.

To change a name in the output, change the `php` row of `hanzoai/openapi`'s
`sdks.yaml` — that is where this client's namespace, layout and name corrections
are declared.

## License

Apache-2.0 or MIT, at your option.
