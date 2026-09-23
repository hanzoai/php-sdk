<?php

/**
 * hello — prove the key works, and print what it can reach.
 *
 * GET /v1/account/keys declares a response schema, so this one comes back as a model:
 * `ApiKeyList` of `ApiKey`, typed, with the prefix and type of every key the
 * caller holds. It is the flow that fails on a bad credential — that is the
 * point of it — where models.php succeeds with none.
 *
 * The key is never printed. `prefix` and `type` are what the server returns to
 * identify a key without disclosing it.
 */

declare(strict_types=1);

require __DIR__ . '/client.php';

use Hanzo\Cloud\Api\AccountApi;

try {
    $keys = (new AccountApi(null, authed()))->getAccountKeys()->getKeys() ?? [];

    printf("%d keys on this account at %s\n", count($keys), base_url());
    foreach ($keys as $key) {
        printf("  %s…  %s\n", $key->getPrefix() ?? '(no prefix)', $key->getType() ?? 'untyped');
    }
} catch (Throwable $e) {
    fail($e);
}
