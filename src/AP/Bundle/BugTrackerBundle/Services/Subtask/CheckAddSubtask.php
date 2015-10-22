<?php


namespace AP\Bundle\BugTrackerBundle\Services\Subtask;

use AP\Bundle\BugTrackerBundle\Entity\Issue;

class CheckAddSubtask implements CheckAddSubtaskInterface
{
    /**
     * @var array
     */
    private static $allowedTypes = [
        Issue::TYPE_STORY
    ];

    /**
     * @param Issue $issue
     * @return bool
     */
    public function check(Issue $issue)
    {
        return in_array($issue->getType(), self::$allowedTypes, true);
    }
}
