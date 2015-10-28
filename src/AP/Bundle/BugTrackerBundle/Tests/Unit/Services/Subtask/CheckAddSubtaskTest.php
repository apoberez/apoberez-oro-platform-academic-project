<?php


namespace AP\Bundle\BugTrackerBundle\Tests\Unit\Services\Subtask;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use AP\Bundle\BugTrackerBundle\Services\Subtask\CheckAddSubtask;

class CheckAddSubtaskTest extends \PHPUnit_Framework_TestCase
{
    public function testSubtaskCanBeCreated()
    {
        $service = new CheckAddSubtask();
        $issue = new Issue();
        $issue->setType(Issue::TYPE_STORY);

        $this->assertTrue($service->check($issue));
    }

    public function testPrentIssueWrongType()
    {
        $service = new CheckAddSubtask();
        $issue = new Issue();
        $issue->setType(Issue::TYPE_BUG);

        $this->assertFalse($service->check($issue));
    }
}
