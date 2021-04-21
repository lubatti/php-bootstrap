<?php

declare (strict_types=1);

namespace App\Config\Application\Bootstrap;

use App\Config\Application\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use Exception;

class Container implements ContainerInterface
{
    private array $instances = [];

    /**
     * {@inheritDoc}
     */
    public function get(string $id)
    {
        $item = $this->resolve($id);

        if (!($item instanceof ReflectionClass)) {
            return $item;
        }

        return $this->getInstance($item);
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $id): bool
    {
        try {
            $item = $this->resolve($id);
        } catch (NotFoundException $e) {
            return false;
        }

        if ($item instanceof ReflectionClass) {
            return $item->isInstantiable();
        }

        return isset($item);
    }

    private function resolve($id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        $object = $this->build($id);

        $this->instances[$id] = $object;

        return $object;
    }

    /**
     * @throws ReflectionException
     */
    private function getInstance(ReflectionClass $item): object
    {
        $constructor = $item->getConstructor();

        if (is_null($constructor) || $constructor->getNumberOfRequiredParameters() == 0) {
            return $item->newInstance();
        }

        $params = [];

        foreach ($constructor->getParameters() as $param) {
            if ($type = $param->getType()) {
                $params[] = $this->get($type->getName());
            }
        }

        return $item->newInstanceArgs($params);
    }

    /**
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws Exception
     */
    private function build($concrete)
    {
        try {
            $reflector = new ReflectionClass($concrete);
        } catch (ReflectionException $e) {
            throw new NotFoundException("Target class [$concrete] does not exist.");
        }

        if (! $reflector->isInstantiable()) {
            throw new Exception("Target class [$concrete] is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();

        $instances = $this->resolveDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    private function resolveDependencies(array $dependencies): array
    {
        $results = [];

        foreach ($dependencies as $dependency) {
            $results[] = $this->resolve($dependency);
        }

        return $results;
    }
}
