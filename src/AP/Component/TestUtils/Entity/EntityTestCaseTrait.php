<?php

namespace AP\Component\TestUtils\Entity;

trait EntityTestCaseTrait
{
    /**
     * @param $entity
     * @param array $data
     * @return mixed
     */
    public static function setEntityData($entity, array $data)
    {
        $className = get_class($entity);
        $reflectionClass = new \ReflectionClass($className);

        foreach ($data as $propertyName => $value) {
            $property = $reflectionClass->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($entity, $value);
        }

        return $entity;
    }

    /**
     * @param $entity
     * @param array $expected
     */
    public static function assertEntityGetters($entity, array $expected)
    {
        foreach ($expected as $property => $value) {
            $actualValue = $entity->{self::getAccessMethodName($entity, $property)}();
            \PHPUnit_Framework_Assert::assertSame($value, $actualValue);
        }
    }

    /**
     * @param $entity
     * @param $property
     * @return string
     */
    private static function getAccessMethodName($entity, $property)
    {
        $availablePrefixes = ['get', 'is'];

        foreach ($availablePrefixes as $prefix) {
            $method = $prefix . ucfirst($property);
            if (method_exists($entity, $method)) {
                return $method;
            }
        }

        throw new \InvalidArgumentException(sprintf(
            "Getter for %s property, doesn't exist in %s class",
            $property,
            get_class($entity)
        ));
    }
}
