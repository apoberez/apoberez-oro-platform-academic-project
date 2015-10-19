<?php


namespace AP\Bundle\TaskTrackerBundle\Controller;

use AP\Bundle\TaskTrackerBundle\Entity\Issue;
use AP\Bundle\TaskTrackerBundle\Form\Handler\IssueHandler;
use AP\Bundle\TaskTrackerBundle\Form\Type\IssueType;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IssueController extends Controller
{
    /**
     * @Route("/", name="task_tracker.issue_index")
     * @Template
     * @Acl(
     *     id="task_tracker.issue_view",
     *     type="entity",
     *     class="APTaskTrackerBundle:Issue",
     *     permission="VIEW"
     * )
     * @return array
     */
    public function indexAction()
    {
        return [
            'entity_class' => 'AP\Bundle\TaskTrackerBundle\Entity\Issue'
        ];
    }

    /**
     * @Route("/create", name="task_tracker.issue_create")
     * @Template("APTaskTrackerBundle:Issue:update.html.twig")
     * @Acl(
     *     id="task_tracker.issue_create",
     *     type="entity",
     *     class="APTaskTrackerBundle:Issue",
     *     permission="CREATE"
     * )
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        return $this->update(new Issue(), $request);
    }

    /**
     * @Route("/{id}", name="task_tracker.issue_view", requirements={"id"="\d+"})
     * @Template
     * @param Issue $issue
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        return ['entity' => $issue];
    }

    /**
     * @Route("/update/{id}", name="task_tracker.issue_update", requirements={"id":"\d+"})
     * @Template()
     * @Acl(
     *     id="task_tracker.issue_update",
     *     type="entity",
     *     class="APTaskTrackerBundle:Issue",
     *     permission="EDIT"
     * )
     * @param Issue $issue
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Issue $issue, Request $request)
    {
        return $this->update($issue, $request);
    }

    /**
     * @param Issue $paymentTerm
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function update(Issue $paymentTerm, Request $request)
    {
        $form = $this->createForm(IssueType::NAME, $paymentTerm);
        $handler = new IssueHandler(
            $form,
            $request,
            $this->getDoctrine()->getManagerForClass('APTaskTrackerBundle:Issue')
        );

        return $this->get('oro_form.model.update_handler')->handleUpdate(
            $paymentTerm,
            $form,
            function (Issue $issue) {
                return [
                    'route' => 'task_tracker.issue_update',
                    'parameters' => ['id' => $issue->getId()]
                ];
            },
            function (Issue $issue) {
                return [
                    'route' => 'task_tracker.issue_view',
                    'parameters' => ['id' => $issue->getId()]
                ];
            },
            $this->get('translator')->trans('ap.task_tracker.controller.issue.saved.message'),
            $handler
        );
    }
}
