<?php


namespace AP\Bundle\BugTrackerBundle\Form\Handler;

use AP\Bundle\BugTrackerBundle\Entity\Issue;

interface FormHandlerInterface
{
    /**
     * @param Issue $issue
     * @return bool
     */
    public function handleBeforeFlush(Issue $issue);
}
