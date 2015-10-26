<?php


namespace AP\Bundle\BugTrackerBundle\Entity;

use Oro\Bundle\UserBundle\Entity\User;

interface CollaboratorAwareInterface
{
    /**
     * @param User $user
     * @return self
     */
    public function addCollaborator(User $user);
}
