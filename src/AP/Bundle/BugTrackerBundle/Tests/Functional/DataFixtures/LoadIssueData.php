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
//        $resolution = $manager->getRepository('APBugTrackerBundle:Resolution')->findOneBy([]);
//        $priority = $manager->getRepository('APBugTrackerBundle:Priority')->findOneBy([]);

        $issue = $this->createIssueForSubtaskTest($manager);
        $manager->persist($issue);

//        for ($i = 1; $i < 21; $i++) {
//            $issue = new Issue();
//            $issue->setDescription("Test issue $i description.")
//                ->setCode("BUG-$i")
//                ->setPriority($priority)
//                ->setType(Issue::TYPE_STORY)
//                ->setResolution($resolution)
//                ->setSummary("Test issue $i summary.")
//            ;
//            $manager->persist($issue);
//        }

        $manager->flush();

        $issues = $manager->getRepository('APBugTrackerBundle:Issue')->findAll();
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @return Issue
     */
    public function createIssueForSubtaskTest(ObjectManager $manager)
    {
        $issue = new Issue();
        $issue->setType(Issue::TYPE_STORY)
            ->setCode('STORY-1')
            ->setType(Issue::TYPE_STORY)
            ->setPriority($manager->getRepository('APBugTrackerBundle:Priority')->findOneBy([]))
            ->setDescription('Subtask available issue description')
            ->setReporter($manager->getRepository('OroUserBundle:User')->find(1))
            ->setSummary('Subtask available issue')
        ;

        return $issue;
    }

//    /**
//     * @param ObjectManager $manager
//     * @return \Oro\Bundle\UserBundle\Entity\User
//     */
//    public function getPriori(ObjectManager $manager)
//    {
//        return ;
//    }


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
