<?php


namespace AP\Bundle\BugTrackerBundle\Tests\Unit\Form\DataProvider;

use AP\Bundle\BugTrackerBundle\Entity\Repository\WorkflowStepRepository;
use AP\Bundle\BugTrackerBundle\Form\DataProvider\IssueFormDataProvider;
use AP\Component\ObjectAccessUtils\ObjectPrivatePropertiesSetter;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

class IssueFormDataProviderTest extends \PHPUnit_Framework_TestCase
{
    use ObjectPrivatePropertiesSetter;

    /**
     * @var IssueFormDataProvider
     */
    protected $provider;

    public function setUp()
    {
        $step1 = new WorkflowStep();
        $this->setObjectProperties($step1, ['id' => 1, 'label' => 'Open']);

        $step2 = new WorkflowStep();
        $this->setObjectProperties($step2, ['id' => 2, 'label' => 'In progress']);

        $repository = $this->getMockBuilder('AP\Bundle\BugTrackerBundle\Entity\Repository\WorkflowStepRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $repository->expects($this->any())->method('findAllByFlowName')
            ->will($this->returnValue([$step1, $step2]));
        $repository->expects($this->any())->method('findAllActiveSteps')
            ->will($this->returnValue([$step1, $step2]));

        /** @var WorkflowStepRepository $repository */
        $this->provider = new IssueFormDataProvider($repository);
    }

    public function testGetTypeChoices()
    {
        $this->assertSame([
            'story' => 'story',
            'bug' => 'bug',
            'subtask' => 'subtask',
            'task' => 'task',
        ], $this->provider->getTypeChoices());
    }

    public function testGetSubtaskTypeChoices()
    {
        $this->assertSame([
            'subtask' => 'subtask',
        ], $this->provider->getSubtaskTypeChoices());
    }

    public function testGetWorkflowStatusChoices()
    {
        $this->assertSame([1 => 'Open', 2 => 'In progress'], $this->provider->getWorkflowStatusChoices());
    }

    public function testGetWorkflowActiveStatuses()
    {
        $this->assertSame([1, 2], $this->provider->getWorkflowActiveStatuses());
    }
}
