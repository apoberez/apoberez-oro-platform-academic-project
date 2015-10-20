<?php


namespace AP\Component\TestUtils\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TypeTestCase extends \Symfony\Component\Form\Test\TypeTestCase
{
    /**
     * @return EntityType
     */
    public function getFormEntityType()
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
            ->setMethods(['getLoader', 'configureOptions'])
            ->disableOriginalConstructor()
            ->getMock();

//        $mockEntityType->expects($this->any())
//            ->method('configureOptions')
//            ->will();

        $mockEntityType->expects($this->any())
            ->method('getLoader')
            ->will($this->returnValue($mockEntityLoader));

        return $mockEntityType;
    }
}
