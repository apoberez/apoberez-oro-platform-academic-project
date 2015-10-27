<?php

namespace AP\Component\TestUtils\Entity;

use AP\Component\ObjectAccessUtils\ObjectPrivatePropertiesSetter;

trait EntityTestCaseTrait
{
    use ObjectPrivatePropertiesSetter;

    /**
     * @param $entity
     * @param array $data
     * @return mixed
     */
    public function setEntityData($entity, array $data)
    {
        return $this->setObjectProperties($entity, $data);
    }

    /**
     * @param $entity
     * @param array $expected
     */
    public function assertEntityGetters($entity, array $expected)
    {
        foreach ($expected as $property => $value) {
            $actualValue = $entity->{self::getAccessMethodName($entity, $property)}();
            \PHPUnit_Framework_Assert::assertSame($value, $actualValue);
        }
    }

    /**
     * @param $entity
     * @param array $dataToSet
     * @param array|null $expectedData
     */
    public function assertEntitySetters($entity, array $dataToSet, array $expectedData = null)
    {
        $expectedData = $expectedData ?: $dataToSet;
        $reflectionClass = new \ReflectionClass(get_class($entity));

        foreach ($dataToSet as $propertyName => $value) {
            $result = $entity->{'set' . ucfirst($propertyName)}($value);
            \PHPUnit_Framework_TestCase::assertSame($entity, $result);
        }

        foreach ($expectedData as $propertyName => $value) {
            $property = $reflectionClass->getProperty($propertyName);
            $property->setAccessible(true);
            \PHPUnit_Framework_Assert::assertSame($expectedData[$propertyName], $property->getValue($entity));
        }
    }

    /**
     * @param $entity
     * @param $property
     * @return string
     */
    private function getAccessMethodName($entity, $property)
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
