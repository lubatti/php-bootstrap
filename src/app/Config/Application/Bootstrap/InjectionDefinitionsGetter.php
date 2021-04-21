<?php

declare (strict_types=1);

namespace App\Config\Application\Bootstrap;

use Psr\Container\ContainerInterface;
use function DI\factory;

class InjectionDefinitionsGetter
{
    public static function get(): array
    {
        return [
            ContainerInterface::class => factory(fn (ContainerInterface $c) => $c),
        ];
    }
}
