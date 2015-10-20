<?php


namespace AP\Bundle\BugTrackerBundle\Tests\Functional\DataFixtures;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIssueData extends AbstractFixture implements DependentFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $issue = new Issue();

        $resolution = $manager->getRepository('APBugTrackerBundle:Resolution')->find(1);
        $priority = $manager->getRepository('APBugTrackerBundle:Priority')->find(1);

        $issue->setDescription('Test issue description.')
            ->setCode('AP-1')
            ->setPriority($priority)
            ->setResolution($resolution)
            ->setSummary('Test issue summary.')
        ;

        $manager->persist($issue);
        $manager->flush();
    }


    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'AP\Bundle\BugTrackerBundle\Migrations\Data\ORM\LoadPriorities',
            'AP\Bundle\BugTrackerBundle\Migrations\Data\ORM\LoadResolutions'
        ];
    }
}
