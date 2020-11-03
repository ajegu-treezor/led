<?php


namespace App\Repositories\Entity;


use ReflectionClass;
use ReflectionException;

abstract class Entity
{

    public static function fill(array $attributes): self {
        $reflector = new ReflectionClass(static::class);
        $entity = new static();
        array_walk($attributes, function($value, $key) use ($reflector, $entity) {
            $methodName = 'set' . ucfirst($key);
            try {
                $method = $reflector->getMethod($methodName);
                $method->invoke($entity, $value);
            } catch (ReflectionException $exception) {
                // nothing
            }
        });

        return $entity;
    }

    public function toArray(): array
    {
        $reflector = new ReflectionClass($this);
        $result = [];
        foreach ($reflector->getProperties() as $property) {
            $methodName = 'get' . ucfirst($property->getName());
            try {
                $method = $reflector->getMethod($methodName);
                $result[$property->getName()] = $method->invoke($this);
            } catch (ReflectionException $exception) {
                // nothing
            }
        }
        return $result;
    }

}
