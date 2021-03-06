<?php


namespace AP\Bundle\BugTrackerBundle\Controller;

use AP\Bundle\BugTrackerBundle\Entity\Issue;

use Oro\Bundle\SecurityBundle\Annotation\Acl;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IssueController
 * @package AP\Bundle\BugTrackerBundle\Controller
 *
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="bug_tracker.issue_index")
     * @Template
     * @Acl(
     *     id="bug_tracker.issue_view",
     *     type="entity",
     *     class="APBugTrackerBundle:Issue",
     *     permission="VIEW"
     * )
     * @return array
     */
    public function indexAction()
    {
        return [
            'entity_class' => 'AP\Bundle\BugTrackerBundle\Entity\Issue'
        ];
    }

    /**
     * @Route("/create", name="bug_tracker.issue_create")
     * @Template("APBugTrackerBundle:Issue:update.html.twig")
     * @Acl(
     *     id="bug_tracker.issue_create",
     *     type="entity",
     *     class="APBugTrackerBundle:Issue",
     *     permission="CREATE"
     * )
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $issue = new Issue();
        $issue->setReporter($this->getUser());
        $assigneeId = $request->query->get('assigneeId');
        if ($assigneeId && $assignee = $this->getDoctrine()->getRepository('OroUserBundle:User')->find($assigneeId)) {
            $issue->setAssignee($assignee);
        }

        $result = $this->update($issue);

        if ($request->query->get('_wid')) {
            $result['formAction'] = $this->generateUrl('bug_tracker.issue_create');
        }

        return $result;
    }

    /**
     * @Route("/{id}", name="bug_tracker.issue_view", requirements={"id"="\d+"})
     * @Template
     * @param Issue $issue
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        return [
            'entity' => $issue,
            'canHaveSubtask' => $this->get('ap.bug_tracker.subtask_add_permission_checker')->check($issue)
        ];
    }

    /**
     * @Route("/update/{id}", name="bug_tracker.issue_update", requirements={"id":"\d+"})
     * @Template()
     * @Acl(
     *     id="bug_tracker.issue_update",
     *     type="entity",
     *     class="APBugTrackerBundle:Issue",
     *     permission="EDIT"
     * )
     * @param Issue $issue
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Issue $issue)
    {
        return $this->update($issue);
    }

    /**
     * @Route("/{id}/subtask", name="bug_tracker.issue_subtask_create", requirements={"id"="\d+"})
     * @Template("APBugTrackerBundle:Issue:update.html.twig")
     * @param Issue $issue
     * @return array
     */
    public function createSubtasckAction(Issue $issue)
    {
        if ($this->get('ap.bug_tracker.subtask_add_permission_checker')->check($issue)) {
            $subtask = new Issue();
            $subtask
                ->setReporter($this->getUser())
                ->setParentIssue($issue);
            return $this->update($subtask);
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @param Issue $issue
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function update(Issue $issue)
    {
        $handler = $this->get('ap.bug_tracker.issue_form_handler');
        $form = $handler->getForm();

        $result = $this->get('oro_form.model.update_handler')->handleUpdate(
            $issue,
            $form,
            function (Issue $issue) {
                return [
                    'route' => 'bug_tracker.issue_update',
                    'parameters' => ['id' => $issue->getId()]
                ];
            },
            function (Issue $issue) {
                return [
                    'route' => 'bug_tracker.issue_view',
                    'parameters' => ['id' => $issue->getId()]
                ];
            },
            $this->get('translator')->trans('ap.bug_tracker.controller.issue.saved_message'),
            $handler
        );

        return $result;
    }
}
