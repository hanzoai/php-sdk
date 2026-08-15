<?php

/**
 * Where the API is, who we are, and how to print a failure. Every flow includes
 * this and nothing else configures a client.
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Hanzo\Cloud\ApiException;
use Hanzo\Cloud\Configuration;

/** Default host. `HANZO_BASE_URL` overrides it (staging, a local cloud, a tunnel). */
function base_url(): string
{
    return getenv('HANZO_BASE_URL') ?: 'https://api.hanzo.ai';
}

/** No credential. The four operations the document marks `security: []` take this. */
function anon(): Configuration
{
    return (new Configuration())->setHost(base_url());
}

/**
 * The credential goes in `accessToken`, and that is the only place it goes: the
 * document declares one securityScheme — `bearer` — and applies it to every
 * operation that does not opt out, so 191 of the 192 API classes read this field
 * and write `Authorization: Bearer <token>` themselves.
 *
 * Fails on the unset variable rather than on the 401 it causes three frames later.
 */
function authed(): Configuration
{
    $key = getenv('HANZO_API_KEY');
    if (!$key) {
        fwrite(STDERR, "HANZO_API_KEY is not set — export an IAM access token or an API key\n");
        exit(1);
    }
    return anon()->setAccessToken($key);
}

/** The server's own message, which ApiException carries and `getMessage()` does not. */
function fail(Throwable $e): never
{
    if ($e instanceof ApiException) {
        fwrite(STDERR, sprintf("HTTP %d: %s\n", $e->getCode(), (string) $e->getResponseBody()));
    } else {
        fwrite(STDERR, $e->getMessage() . "\n");
    }
    exit(1);
}
