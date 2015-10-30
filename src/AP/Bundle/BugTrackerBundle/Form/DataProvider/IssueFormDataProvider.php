<?php


namespace AP\Bundle\BugTrackerBundle\Form\DataProvider;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use AP\Bundle\BugTrackerBundle\Entity\Repository\WorkflowStepRepository;
use AP\Bundle\BugTrackerBundle\Services\Subtask\CheckAddSubtask;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

class IssueFormDataProvider implements IssueFormDataProviderInterface
{
    /**
     * @var WorkflowStepRepository
     */
    private $workflowStepRepository;

    /**
     * IssueFormDataProvider constructor.
     * @param WorkflowStepRepository $workflowStepRepository
     */
    public function __construct(WorkflowStepRepository $workflowStepRepository)
    {
        $this->workflowStepRepository = $workflowStepRepository;
    }

    /**
     * @return array
     */
    public function getWorkflowStatusChoices()
    {
        $result = [];
        $steps = $this->workflowStepRepository->findAllByFlowName();
        foreach ($steps as $step) {
            $result[$step->getId()] = $step->getLabel();
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getWorkflowActiveStatuses()
    {
        $result = array_map(function ($item) {
            /** @var WorkflowStep $item */
            return $item->getId();
        }, $this->workflowStepRepository->findAllActiveSteps());
        return $result;
    }

    /**
     * @return array
     */
    public function getTypeChoices()
    {
        return array_combine(Issue::getTypes(), Issue::getTypes());
    }

    /**
     * @return array
     */
    public function getSubtaskTypeChoices()
    {
        return array_combine(CheckAddSubtask::$allowedTypes, CheckAddSubtask::$allowedTypes);
    }
}
