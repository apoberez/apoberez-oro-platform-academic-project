<?php


namespace AP\Bundle\BugTrackerBundle\Form\Handler;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use AP\Bundle\BugTrackerBundle\Form\Type\IssueType;
use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\FormBundle\Event\FormHandler\AfterFormProcessEvent;
use Oro\Bundle\FormBundle\Event\FormHandler\Events;
use Oro\Bundle\FormBundle\Event\FormHandler\FormProcessEvent;
use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class IssueFormHandler implements TagHandlerInterface
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     *
     * @param FormInterface $form
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher
    ) {
        $this->form = $form;
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Issue $issue
     * @return bool|null
     * @throws \Exception
     */
    public function process(Issue $issue)
    {
        $event = new FormProcessEvent($this->form, $issue);
        $this->dispatcher->dispatch(Events::BEFORE_FORM_DATA_SET, $event);

        if ($event->isFormProcessInterrupted()) {
            return null;
        }
        $this->form->setData($issue);

        if (in_array($this->request->getMethod(), ['POST', 'PUT'], true)) {
            $event = new FormProcessEvent($this->form, $issue);
            $this->dispatcher->dispatch(Events::BEFORE_FORM_SUBMIT, $event);

            if ($event->isFormProcessInterrupted()) {
                return null;
            }

            $this->submitForm($this->request);
            if ($this->form->isValid()) {
                $this->onSuccess($issue);
                return $issue;
            }
        }

        return null;
    }

    /**
     * @param Request $request
     */
    protected function submitForm(Request $request)
    {
        $data = $this->request->request->get(IssueType::NAME) ?: $this->request;
        $this->form->submit($data, "POST" === $request->getMethod());
    }

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
     * @throws \Exception
     */
    protected function onSuccess(Issue $issue)
    {
        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->persist($issue);
            $this->dispatcher->dispatch(Events::BEFORE_FLUSH, new AfterFormProcessEvent($this->getForm(), $issue));
            $this->entityManager->flush();
            $this->dispatcher->dispatch(Events::AFTER_FLUSH, new AfterFormProcessEvent($this->getForm(), $issue));
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        $this->handleAfterFlush($issue);
    }

    /**
     * @param Issue $issue
     * @return bool
     *
     * If more complex logic will appear
     * it's recommended create event
     */
    protected function handleAfterFlush(Issue $issue)
    {
        $issue->setCode(sprintf('%s-%s', mb_strtoupper($issue->getType()), $issue->getId()));
        $this->entityManager->persist($issue);

        $this->tagManager->saveTagging($issue, false);
        $this->entityManager->flush();
    }
}
