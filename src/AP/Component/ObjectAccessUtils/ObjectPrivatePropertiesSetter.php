<?php


namespace AP\Component\ObjectAccessUtils;

trait ObjectPrivatePropertiesSetter
{
    /**
     * @param $object
     * @param array $data
     * @return mixed
     */
    public function setObjectProperties($object, array $data)
    {
        $className = get_class($object);
        $reflectionClass = new \ReflectionClass($className);

        foreach ($data as $propertyName => $value) {
            $property = $reflectionClass->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }

        return $object;
    }
}
