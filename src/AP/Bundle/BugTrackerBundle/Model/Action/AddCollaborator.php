<?php


namespace AP\Bundle\BugTrackerBundle\Model\Action;

use Oro\Bundle\WorkflowBundle\Exception\InvalidParameterException;
use Oro\Bundle\WorkflowBundle\Model\Action\AbstractAction;
use Oro\Bundle\WorkflowBundle\Model\Action\ActionInterface;

class AddCollaborator extends AbstractAction
{

    /**
     * @param mixed $context
     */
    protected function executeAction($context)
    {
        // TODO: Implement executeAction() method.
    }

    /**
     * Initialize action based on passed options.
     *
     * @param array $options
     * @return ActionInterface
     * @throws InvalidParameterException
     */
    public function initialize(array $options)
    {

        if ((count($options) < 2) || (count($options) > 3)) {
            throw new InvalidParameterException('Two or three parameters are required.');
        }

        if (isset($options['email'])) {
            $this->activityEntity = $options['email'];
        } elseif (isset($options[0])) {
            $this->activityEntity = $options[0];
        } else {
            throw new InvalidParameterException('Parameter "email" has to be set.');
        }

        if (isset($options['target_entity'])) {
            $this->targetEntity = $options['target_entity'];
        } elseif (isset($options[1])) {
            $this->targetEntity = $options[1];
        } else {
            throw new InvalidParameterException('Parameter "target_entity" has to be set.');
        }

        if (isset($options['attribute'])) {
            $this->attribute = $options['attribute'];
        } elseif (isset($options[2])) {
            $this->attribute = $options[2];
        }
    }
}