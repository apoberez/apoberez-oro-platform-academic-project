<?php


namespace AP\Bundle\BugTrackerBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IssueController
 * @package AP\Bundle\BugTrackerBundle\Controller\Api\Rest
 *
 * @RouteResource("bug-tracker/issue")
 * @NamePrefix("ap_bug_tracker_api_")
 */
class IssueController extends RestController
{
    /**
     * REST GET list
     *
     * @QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Page number, starting from 1. Defaults to 1."
     * )
     * @QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Number of items per page. defaults to 10."
     * )
     * @ApiDoc(
     *      description="Get all issue items",
     *      resource=true
     * )
     * @AclAncestor("bug_tracker.issue_view")
     * @return Response
     */
    public function getListAction()
    {
        return $this->handleGetListRequest();
    }

    /**
     * REST GET item
     *
     * @param int $id
     *
     * @ApiDoc(
     *      description="Get issue item",
     *      resource=true
     * )
     * @AclAncestor("bug_tracker.issue_view")
     * @return Response
     */
    public function getItemAction($id)
    {
        return $this->handleGetRequest($id);
    }

    /**
     * REST PUT
     *
     * @param int $id
     *
     * @ApiDoc(
     *      description="Update issue",
     *      resource=true
     * )
     * @AclAncestor("bug_tracker.issue_update")
     * @return Response
     */
    public function updateAction($id)
    {
        return $this->handleUpdateRequest($id);
    }

    /**
     * @ApiDoc(
     *      description="Create new issue",
     *      resource=true
     * )
     * @AclAncestor("bug_tracker.issue_create")
     */
    public function createAction()
    {
        return $this->handleCreateRequest();
    }

    /**
     * @param int $id
     *
     * @ApiDoc(
     *      description="Delete issue",
     *      resource=true
     * )
     * @Acl(
     *      id="bug_tracker.issue_delete",
     *      type="entity",
     *      permission="DELETE",
     *      class="APBugTrackerBundle:Issue"
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * Get entity Manager
     *
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->get('ap.bug_tracker.manager.issue_api');
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->get('ap.bug_tracker.form.issue_api');
    }

    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        return $this->get('ap.bug_tracker.issue_form_handler');
    }
}
