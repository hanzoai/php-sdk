<?php

/**
 * models — the catalog, with no credential at all.
 *
 * GET /v1/models is one of four operations the document marks `security: []`,
 * so the client sends no Authorization header and this program runs for a reader
 * who has no key. It is the flow to reach for when the question is "does the
 * client talk to the server", separate from "is my key any good".
 *
 * It also declares no response schema — one of the operations that state the
 * route and not its shape — so `getModels()` returns void and the body arrives
 * through the request builder, which is public for exactly this. Everything that
 * makes the call a call (host, path, headers, auth) still comes from the client.
 */

declare(strict_types=1);

require __DIR__ . '/client.php';

use GuzzleHttp\Client;
use Hanzo\Cloud\Api\AiApi;

try {
    $http = new Client();
    $api = new AiApi($http, anon());

    $body = (string) $http->send($api->getModelsRequest())->getBody();
    $catalog = json_decode($body, true, 512, JSON_THROW_ON_ERROR)['data'] ?? [];

    printf("%d models from %s\n", count($catalog), base_url());
    foreach (array_slice($catalog, 0, 5) as $model) {
        printf("  %s  (%s)\n", $model['id'], $model['owned_by'] ?? $model['provider'] ?? 'unattributed');
    }
} catch (Throwable $e) {
    fail($e);
}
