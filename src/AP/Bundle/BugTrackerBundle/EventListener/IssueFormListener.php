<?php


namespace AP\Bundle\BugTrackerBundle\EventListener;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use AP\Bundle\BugTrackerBundle\Form\Handler\FormHandlerInterface;
use Oro\Bundle\FormBundle\Event\FormHandler\AfterFormProcessEvent;
use Oro\Bundle\FormBundle\Event\FormHandler\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IssueFormListener implements EventSubscriberInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $handler;

    /**
     * IssueFormListener constructor.
     * @param FormHandlerInterface $handler
     */
    public function __construct(FormHandlerInterface $handler)
    {
        $this->handler = $handler;
    }


    /**
     * @param AfterFormProcessEvent $event
     */
    public function onBeforeFlush(AfterFormProcessEvent $event)
    {
        if (is_object($event->getData()) && $event->getData() instanceof Issue) {
            $this->handler->handleBeforeFlush($event->getData());
        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::BEFORE_FLUSH => 'onBeforeFlush'
        ];
    }
}
