<?php

namespace AP\Bundle\BugTrackerBundle\Tests\Unit\Form\Type;

use AP\Bundle\BugTrackerBundle\Entity\Repository\WorkflowStepRepository;
use AP\Bundle\BugTrackerBundle\Form\DataProvider\IssueFormDataProvider;
use AP\Bundle\BugTrackerBundle\Form\Type\IssueType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvent;

class IssueTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueType()
     */
    private $type;

    public function setUp()
    {
        parent::setUp();
        /** @var WorkflowStepRepository $mockWorkflowRepository */
        $mockWorkflowRepository = $this
            ->getMockBuilder('AP\Bundle\BugTrackerBundle\Entity\Repository\WorkflowStepRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->type = new IssueType(new IssueFormDataProvider($mockWorkflowRepository));
    }

    public function testBuildForm()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $builder->expects($this->any())
            ->method('add')
            ->will($this->returnSelf());

        /** @var FormBuilder $builder */
        $this->type->buildForm($builder, []);
    }
}
