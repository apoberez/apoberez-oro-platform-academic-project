<?php


namespace AP\Bundle\BugTrackerBundle\Form\Handler;

use AP\Bundle\BugTrackerBundle\Entity\Issue;

class IssueApiHandler implements ApiHandlerInterface
{
    /**
     * IssueApiHandler constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param Issue $issue
     * @return bool
     */
    public function process(Issue $issue)
    {
        // TODO: Implement process() method.
    }
}
