<?php


namespace AP\Bundle\BugTrackerBundle\Services\Subtask;

use AP\Bundle\BugTrackerBundle\Entity\Issue;

interface CheckAddSubtaskInterface
{
    /**
     * @param Issue $issue
     * @return bool
     */
    public function check(Issue $issue);
}
