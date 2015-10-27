<?php


namespace AP\Bundle\BugTrackerBundle\Migrations\Data\ORM;

use AP\Bundle\BugTrackerBundle\Entity\Priority;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPriorities extends AbstractFixture
{
    /**
     * @return array
     */
    protected function createPriorities()
    {
        $priorities = [];

        $priorities[] = (new Priority())
            ->setName('critical')
            ->setLabel('Critical')
            ->setDescription('Serious problem that could block progress.')
            ->setOrder(1);

        $priorities[] = (new Priority())
            ->setName('major')
            ->setLabel('Major')
            ->setDescription('Has a problem to affect progress.')
            ->setOrder(2);

        $priorities[] = (new Priority())
            ->setName('trivial')
            ->setLabel('Trivial')
            ->setDescription('Minor problem or easily worked around.')
            ->setOrder(3);

        return $priorities;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->createPriorities() as $priority) {
            $manager->persist($priority);
        }

        $manager->flush();
    }
}
