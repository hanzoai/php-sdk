# @hanzo php-sdk

Landing page for the **Hanzo PHP SDK**. This is the thin wrapper in the Hanzo
umbrella org; generated clients live in their own language org, the way
[hanzo-rs/sdk](https://github.com/hanzo-rs/sdk),
[hanzo-go/sdk](https://github.com/hanzo-go/sdk) and
[hanzo-swift/sdk](https://github.com/hanzo-swift/sdk) do.

## Status

**Not generated yet.** There is no PHP client for the Hanzo API today, and this
page exists so that fact is findable rather than guessed at.

## What it will be

The whole Hanzo `/v1` surface — 2479 operations — as an idiomatic PHP package,
generated from the same OpenAPI document every other Hanzo SDK is generated
from. A language is a row in `sdks.yaml`, not a bespoke pipeline:

```yaml
php:
  repo: php-sdk
  generator: php
```

Adding it needs that row and a repository in a `hanzo-php` org to hold the
output. Until both exist, this page is the honest answer.

## Meanwhile

The API is HTTP and documented — <https://api.hanzo.ai/v1/openapi.json> — and
every route answers to `Authorization: Bearer <token>`, where the token is an
IAM access token or an API key (`pk-` publishable, `sk-` secret). Any PHP HTTP
client reaches it today.
