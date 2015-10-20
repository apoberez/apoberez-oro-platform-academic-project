<?php


namespace AP\Bundle\BugTrackerBundle\Form\Handler;

use AP\Bundle\BugTrackerBundle\Entity\Issue;

interface ApiHandlerInterface
{
    /**
     * @param Issue $issue
     * @return bool
     */
    public function process(Issue $issue);
}
