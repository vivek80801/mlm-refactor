<?php

namespace App\Core;

use App\Core\Exceptions\AppException;
use ReflectionClass;
use ReflectionNamedType;

class Container
{
    private array $instances = [];
    private array $bindings = [];
    private array $resolving = [];
    public function get
    (
        string $className
    ): object
    {
        $className = $this->bindings[$className] ?? $className;
    
        if (isset($this->instances[$className]))
        {
            return $this->instances[$className];
        }

        if (in_array($className, $this->resolving))
        {
            $chain = implode(' -> ', [...$this->resolving, $className]);
            throw new AppException("Circular dependency detected: {$chain}");
        }

        $this->resolving[] = $className;

        if (!class_exists($className))
        {
            throw new AppException("Class {$className} does not exist");
        }
    
        $reflector = new ReflectionClass($className);
        $constructor = $reflector->getConstructor();
    
        if ($constructor === null)
        {
            return new $className;
        }
    
        $dependencies = [];
    
        foreach ($constructor->getParameters() as $parameter)
        {
            $type = $parameter->getType();

            if (
                !$type ||
                $type->isBuiltin()
            )
            {
                if (
                    $parameter->isDefaultValueAvailable()
                )
                {
                    $dependencies[] = $parameter
                        ->getDefaultValue();
                    continue;
                }
                throw new AppException("
                    Container Error: Unresolvable dependency => 
                    {$parameter->getName()}"
                );
            }

            if (
                !$type instanceof ReflectionNamedType
            )
            {
                throw new AppException(
                    "Container Error: Unsupported 
                    union/intersection type"
                );
            }

            $dependencies[] = $this->get(
                $type->getName()
            );
        }
        $object = new $className(...$dependencies);
        array_pop($this->resolving);
        return $object;
    }

    public function singleton
    (
        string $class,
        object $instance
    ): void
    {
        $this->instances[$class] = $instance;
    }

    public function bind
    (
        string $abstract,
        string $concrete
    ): void
    {
        if (!is_a($concrete, $abstract, true))
        {
            throw new AppException(
                "{$concrete} must implement {$abstract}"
            );
        }
        $this->bindings[$abstract] = $concrete;
    }
}
