<?php


namespace AP\Bundle\BugTrackerBundle\Form\DataProvider;

interface IssueFormDataProviderInterface
{
    /**
     * @return array
     */
    public function getWorkflowStatusChoices();

    /**
     * @return array
     */
    public function getWorkflowActiveStatuses();

    /**
     * @return array
     */
    public function getTypeChoices();

    /**
     * @return array
     */
    public function getSubtaskTypeChoices();
}
