<?php

declare (strict_types=1);

namespace App\Config\Application\Files;

class ProjectPath
{
    private const APP_DIR = __DIR__ . '/../../../';

    public static function get(string $path): string
    {
        return self::getPaths()[$path];
    }

    private static function getPaths(): array
    {
        $path = self::APP_DIR . ':absolute';

        return [
            Path::DI_PROXIES        => strtr($path, [':absolute' => 'Resources/tmp/di']),
            Path::CONTAINER_PROXIES => strtr($path, [':absolute' => 'Resources/tmp/container']),
        ];
    }
}
