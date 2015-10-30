<?php


namespace AP\Bundle\BugTrackerBundle\Tests\Unit\Form\Type;

use AP\Bundle\BugTrackerBundle\Entity\Repository\WorkflowStepRepository;
use AP\Bundle\BugTrackerBundle\Form\DataProvider\IssueFormDataProvider;
use AP\Bundle\BugTrackerBundle\Form\Type\IssueApiType;

class IssueApiTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueApiType()
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
        $this->type = new IssueApiType(new IssueFormDataProvider($mockWorkflowRepository));
    }

    public function testGetName()
    {
        $this->assertSame(IssueApiType::NAME, $this->type->getName());
    }
}
