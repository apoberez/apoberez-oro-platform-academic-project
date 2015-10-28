<?php


namespace AP\Bundle\BugTrackerBundle\Services\Grid;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use Symfony\Bridge\Doctrine\RegistryInterface;

class IssueGridHelper
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * IssueGridHelper constructor.
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return array
     */
    public function getWorkflowStatusChoices()
    {
        $result = $this->doctrine->getEntityManager()->createQueryBuilder()
            ->select('step.id', 'step.label')
            ->from('OroWorkflowBundle:WorkflowStep', 'step')
            ->leftJoin('step.definition', 'definition')
            ->where('definition.name = :definition_name')
            ->setParameter(':definition_name', Issue::FLOW_NAME)
            ->getQuery()
            ->getArrayResult();
        $result = array_combine(array_column($result, 'id'), array_column($result, 'label'));

        return $result;
    }
}
