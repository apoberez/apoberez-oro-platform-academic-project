<?php

namespace AP\Bundle\BugTrackerBundle\Tests\Unit\Form\Type;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use AP\Bundle\BugTrackerBundle\Entity\Priority;
use AP\Bundle\BugTrackerBundle\Form\Type\IssueType;
use AP\Component\ObjectAccessUtils\ObjectPrivatePropertiesSetter;
use AP\Component\TestUtils\Form\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;

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

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $priority = new Priority();
        $this->setObjectProperties($priority, ['id' => 1, 'name' => 'testPriority']);

        $priorityEntityType = $this->getFormEntityType();

        return [new PreloadedExtension([
            $priorityEntityType->getName() => $priorityEntityType,
        ], [])];
    }
}
