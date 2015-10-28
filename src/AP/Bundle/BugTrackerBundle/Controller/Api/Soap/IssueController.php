<?php


namespace AP\Bundle\BugTrackerBundle\Controller\Api\Soap;

use Oro\Bundle\SoapBundle\Controller\Api\Soap\SoapController;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use Symfony\Component\Form\FormInterface;

class IssueController extends SoapController
{

    /**
     * Get entity Manager
     *
     * @return ApiEntityManager
     */
    public function getManager()
    {
        // TODO: Implement getManager() method.
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        // TODO: Implement getForm() method.
    }

    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        // TODO: Implement getFormHandler() method.
    }
}