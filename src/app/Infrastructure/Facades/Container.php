<?php

declare (strict_types=1);

namespace App\Infrastructure\Facades;

use Psr\Container\ContainerInterface;

/**
 * Special facade to access DI Container
 *
 * @method static get(string $class)
 * @method static set(string $class, object $object)
 */
class Container
{
    private static ContainerInterface $instance;

    /**
     * Set container instance
     */
    public static function setInstance(ContainerInterface $container): void
    {
        static::$instance = $container;
    }

    /**
     * Get current instance
     */
    public static function getInstance(): ?ContainerInterface
    {
        return static::$instance;
    }

    /**
     * Passing calls to wrapped instance
     *
     * @param string    $name       Method name
     * @param array     $arguments  Arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([static::$instance, $name], $arguments);
    }
}
