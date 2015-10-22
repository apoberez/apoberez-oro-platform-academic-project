<?php


namespace AP\Component\TestUtils\Form;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Form\Test\TypeTestCase;

class FormTypeTestCase extends TypeTestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEntityTypeMock()
    {
        $mockEntityLoader = $this->getMockBuilder('Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityLoader->expects($this->any())
            ->method('getEntities')
            ->will($this->returnValue([]));

        $mockEntityLoader->expects($this->any())
            ->method('getEntitiesByIds')
            ->will($this->returnValue([]));

        $mockEntityType  = $this->getMockBuilder('Symfony\Bridge\Doctrine\Form\Type\EntityType')
            ->setMethods(['getLoader'])
            ->setConstructorArgs([$this->getRegistryMock()])
            ->getMock();

        $mockEntityType->expects($this->any())
            ->method('getLoader')
            ->will($this->returnValue($mockEntityLoader));

        return $mockEntityType;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getRegistryMock()
    {
        $mockEntityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRegistry = $this->getMockBuilder('Doctrine\Bundle\DoctrineBundle\Registry')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRegistry->expects($this->any())->method('getManagerForClass')
            ->will($this->returnValue($mockEntityManager));

        $mockEntityManager ->expects($this->any())->method('getClassMetadata')
            ->withAnyParameters()
            ->will($this->returnValue(new ClassMetadata('entity')));

        $repo = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager ->expects($this->any())->method('getRepository')
            ->withAnyParameters()
            ->will($this->returnValue($repo));

        return $mockRegistry;
    }
}
