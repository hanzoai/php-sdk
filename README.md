# @hanzo php-sdk

Landing page for the **Hanzo PHP SDK**. This is the thin wrapper in the Hanzo
umbrella org; generated clients live in their own language org, the way
[hanzo-rs/sdk](https://github.com/hanzo-rs/sdk),
[hanzo-go/sdk](https://github.com/hanzo-go/sdk) and
[hanzo-swift/sdk](https://github.com/hanzo-swift/sdk) do.

## Status

**Declared and generated.** PHP is a row in
[hanzoai/openapi](https://github.com/hanzoai/openapi)'s `sdks.yaml` — the same
one mechanism every other Hanzo client is a projection of — and the client it
produces builds and runs. It publishes from `hanzo-php/sdk`, in the language
org, once that repository exists.

## What it is

The whole Hanzo `/v1` surface — 2479 operations over 1814 paths — as 192 API
classes and 2461 models under the `Hanzo\Cloud` namespace, PHP 8.1+ over Guzzle
7, PSR-4 from `src/`. Generated from the OpenAPI document `hanzoai/cloud` emits
from its own routers, at the commit the client's `.spec-lock` names.

The row picks the stable `php` generator over the two beta ones for a measured
reason: `php-dt` cannot read this document at all, and `php-nextgen` emits the
same classes with cosmetic differences. It also carries the four name
corrections PHP needs — `modelName` collides with the generator's own
`ModelInterface::getModelName()`, and three legacy snake-spelled fields collide
with their camel twins — none of which change anything on the wire.

## Meanwhile

The API is HTTP and documented — <https://api.hanzo.ai/v1/openapi.json> — and
every route answers to `Authorization: Bearer <token>`, where the token is an
IAM access token or an API key (`pk-` publishable, `sk-` secret). Four
operations answer without one: `get_models`, `get_models_providers`,
`get_commands`, `get_openapi.json`.
