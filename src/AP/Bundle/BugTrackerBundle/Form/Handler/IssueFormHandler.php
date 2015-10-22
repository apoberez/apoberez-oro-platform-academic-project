<?php


namespace AP\Bundle\BugTrackerBundle\Form\Handler;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;

class IssueFormHandler implements FormHandlerInterface, TagHandlerInterface
{
    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * Setter for tag manager
     *
     * @param TagManager $tagManager
     */
    public function setTagManager(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    /**
     * @param Issue $issue
     * @return bool
     */
    public function handleBeforeFlush(Issue $issue)
    {
        $this->tagManager->saveTagging($issue, false);
    }
}
