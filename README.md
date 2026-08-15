# hanzoai/hanzoai

The PHP client for the Hanzo API: every operation the document declares — 2479
over 1814 paths, `/v1` and the 48 routes outside it — as 192 API classes and
2461 models, generated from the OpenAPI document `hanzoai/cloud` emits from its
own routers.

## Install

Not on Packagist yet, so `composer require` has nothing to resolve. Take it from
the repository instead:

```json
{
    "repositories": [{ "type": "vcs", "url": "https://github.com/hanzoai/php-sdk" }],
    "require": { "hanzoai/hanzoai": "dev-main" }
}
```

Or build it in place:

```bash
git clone https://github.com/hanzoai/php-sdk.git hanzo-php-sdk
cd hanzo-php-sdk
composer install
```

PHP 8.1 or newer, with `curl`, `json` and `mbstring`. Composer brings Guzzle 7.

## Quickstart

```php
<?php
require 'vendor/autoload.php';

use Hanzo\Cloud\Api\KeysApi;
use Hanzo\Cloud\Configuration;

$config = (new Configuration())
    ->setHost('https://api.hanzo.ai')
    ->setAccessToken(getenv('HANZO_API_KEY'));

$keys = (new KeysApi(null, $config))->getKeys();

printf("%d keys on this account\n", count($keys->getKeys() ?? []));
```

Every API class takes `(?ClientInterface $client, ?Configuration $config,
?HeaderSelector $selector, int $hostIndex)`, all four defaulted, so passing
`null` for the first gives you a default Guzzle client. `ClientInterface` is
`GuzzleHttp\ClientInterface`.

## Auth

The document declares one security scheme — `bearer` — and applies it to every
operation that does not opt out, so the credential goes in exactly one place:

```php
$config->setAccessToken($token);
```

191 of the 192 API classes read that field and write `Authorization: Bearer
<token>` themselves. The token is an IAM access token or an API key — `pk-`
publishable, `sk-` secret. The one class that never sends it is `CommandsApi`,
whose single operation is open.

Four operations carry `security: []` and answer without a credential:
`get_models`, `get_models_providers`, `get_commands`, `get_openapi.json`.

## Untyped responses

There are 2502 operation methods for 2479 operations: 23 of them carry two tags,
so they land in two classes. 834 return `void` — the routes the document states
the address of and not the shape. Their `<operation>Request()` builder is public,
so the PSR-7 request the client would have sent is one call away and the body is
yours to read:

```php
$http = new GuzzleHttp\Client();
$api  = new Hanzo\Cloud\Api\ModelsApi($http, $config);

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

An edit to `src/` is undone by the next release, so it belongs in the `php` row
of `hanzoai/openapi`'s `sdks.yaml` instead — that is where this client's
namespace, layout and four name corrections are declared.

## License

Apache-2.0 or MIT, at your option.
