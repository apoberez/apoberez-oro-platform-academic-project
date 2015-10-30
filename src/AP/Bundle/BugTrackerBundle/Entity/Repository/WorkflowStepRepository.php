<?php


namespace AP\Bundle\BugTrackerBundle\Entity\Repository;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

class WorkflowStepRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var array
     */
    protected static $activeStepsNames = ['open', 'in_progress', 'reopened'];

    /**
     * WorkflowStepRepository constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return WorkflowStep[]
     */
    public function findAllByFlowName()
    {
        return $this->manager->createQueryBuilder()
            ->select('step')
            ->from('OroWorkflowBundle:WorkflowStep', 'step')
            ->leftJoin('step.definition', 'definition')
            ->where('definition.name = :definition_name')
            ->setParameter(':definition_name', Issue::FLOW_NAME)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array|\Oro\Bundle\WorkflowBundle\Entity\WorkflowStep[]
     */
    public function findAllActiveSteps()
    {
        return $this->manager->createQueryBuilder()
            ->select('step')
            ->from('OroWorkflowBundle:WorkflowStep', 'step')
            ->leftJoin('step.definition', 'definition')
            ->where('definition.name = :definition_name')
            ->setParameter(':definition_name', Issue::FLOW_NAME)
            ->andWhere('step.name in (:names)')
            ->setParameter(':names', self::$activeStepsNames)
            ->getQuery()
            ->getResult();
    }
}
