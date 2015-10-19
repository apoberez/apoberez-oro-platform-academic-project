<?php

namespace AP\Bundle\TaskTrackerBundle\Tests\Unit\Form\Type;

use AP\Bundle\TaskTrackerBundle\Entity\Issue;
use AP\Bundle\TaskTrackerBundle\Form\Type\IssueType;
use AP\Component\ObjectAccessUtils\ObjectPrivatePropertiesSetter;
use Symfony\Component\Form\Test\TypeTestCase;

class IssueTypeTest extends TypeTestCase
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

    /**
     * this test should be split in non test project
     */
    public function testSubmitValidData()
    {
        $formData = [
            'summary' => 'test',
            'description' => 'test'
        ];

        $form = $this->factory->create($this->type);

        $object = $this->setObjectProperties(new Issue(), $formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach ($formData as $key => $value) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
