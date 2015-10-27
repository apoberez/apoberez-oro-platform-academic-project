<?php


namespace AP\Bundle\BugTrackerBundle\Migrations\Data\ORM;

use AP\Bundle\BugTrackerBundle\Entity\Resolution;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadResolutions extends AbstractFixture
{
    /**
    * @return array
    */
    protected function createPriorities()
    {
        $priorities = [];

        $priorities[] = (new Resolution())
            ->setName('fixed')
            ->setLabel('Fixed')
            ->setDescription('A fix for this issue is checked in tree and tested.')
            ->setOrder(1);

        $priorities[] = (new Resolution())
            ->setName('duplicate')
            ->setLabel('Duplicate')
            ->setDescription('he problem is duplicate of existing issue.')
            ->setOrder(2);

        $priorities[] = (new Resolution())
            ->setName('will_not_fix')
            ->setLabel('Won\'t fix')
            ->setDescription('The problem described is an issue which will never be fixed.')
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
