<?php

declare(strict_types=1);

namespace App\Tests\functional;

use LogicException;
use function array_key_exists;
use function file_get_contents;
use function json_decode;
use function json_encode;
use function strtolower;
use const JSON_THROW_ON_ERROR;

final class SchemaFactory
{
    public static function createFromResponse(string $path, string $method, int $statusCode): object
    {
        $openapiSpecs = json_decode(
            file_get_contents(__DIR__ . '/../../doc/openapi/openapi.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (false === array_key_exists($path, $openapiSpecs['paths'])) {
            throw new LogicException("[{$path}] Not found in openapi specs.");
        }

        $pathLevel = $openapiSpecs['paths'][$path];

        $method = strtolower($method);

        if (false === array_key_exists($method, $pathLevel)) {
            throw new LogicException("[{$path}][{$method}] Not found in openapi specs.");
        }

        if (false === array_key_exists('responses', $pathLevel[$method])) {
            throw new LogicException("[{$path}][{$method}] No responses defined in openapi specs.");
        }

        $responses = $pathLevel[$method]['responses'];

        if (false === array_key_exists($statusCode, $responses)) {
            throw new LogicException("[{$path}][{$method}][{$statusCode}] Not found in openapi specs.");
        }

        $schema = $responses[$statusCode]['content']['application/json']['schema'] ?? null;

        if (null === $schema) {
            throw new LogicException("[{$path}][{$method}][{$statusCode}] No content or schema was provided for Content-Type: application/json in openapi specs.");
        }

        return json_decode(json_encode($schema, JSON_THROW_ON_ERROR, 512), false, 512, JSON_THROW_ON_ERROR);
    }
}
