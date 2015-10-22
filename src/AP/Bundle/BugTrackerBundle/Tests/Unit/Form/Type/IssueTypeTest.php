<?php

namespace AP\Bundle\BugTrackerBundle\Tests\Unit\Form\Type;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use AP\Bundle\BugTrackerBundle\Form\Type\IssueType;
use AP\Component\ObjectAccessUtils\ObjectPrivatePropertiesSetter;
use AP\Component\TestUtils\Form\FormTypeTestCase;
use Oro\Bundle\EntityBundle\Form\Type\EntitySelectType;
use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\PreloadedExtension;

class IssueTypeTest extends FormTypeTestCase
{
    use ObjectPrivatePropertiesSetter;

    /**
     * @var IssueType()
     */
    private $type;

    public function setUp()
    {
        parent::setUp();

        $this->type = new IssueType();
    }

    public function testSubmitValidData()
    {
//        $formData = [
//            'summary' => 'test',
//            'description' => 'test'
//        ];
//
//        $form = $this->factory->create($this->type);
//
//        $object = $this->setObjectProperties(new Issue(), $formData);
//
//        // submit the data to the form directly
//        $form->submit($formData);
//
//        $this->assertTrue($form->isSynchronized());
//        $this->assertEquals($object, $form->getData());
//
//        $view = $form->createView();
//        $children = $view->children;
//
//        foreach ($formData as $key => $value) {
//            $this->assertArrayHasKey($key, $children);
//        }
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        /** @var EntityType $entityType */
        $entityType = $this->getEntityTypeMock();

        /** @var ConfigManager $configManager */
        $configManager = $this->getMockBuilder('Oro\Bundle\EntityConfigBundle\Config\ConfigManager')
            ->disableOriginalConstructor()
            ->getMock();

        $entitySelectType = new EntitySelectType($configManager);

        return [
            new PreloadedExtension([$entityType->getName() => $entityType], []),
        ];
    }
}
