<?php


namespace AP\Bundle\BugTrackerBundle\Migrations\Data\Demo\ORM;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use OroCRM\Bundle\DemoDataBundle\Migrations\Data\Demo\ORM\AbstractDemoFixture;

class LoadIssueData extends AbstractDemoFixture
{
    const SDFSDF = 'sdfsdf';

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        require_once $this->container->getParameter('kernel.root_dir') . '/../vendor/fzaninotto/faker/src/autoload.php';
        $faker = Factory::create();


        $resolutions = $manager->getRepository('APBugTrackerBundle:Resolution')->findAll();
        $priorities = $manager->getRepository('APBugTrackerBundle:Priority')->findAll();

        for ($i = 1; $i < 21; $i++) {
            $issue = new Issue();
            $issue->setDescription($faker->text(255))
                ->setPriority($priorities[array_rand($priorities)])
                ->setResolution($resolutions[array_rand($resolutions)])
                ->setReporter($this->getRandomUserReference())
                ->setAssignee($this->getRandomUserReference())
                ->setType(Issue::getTypes()[array_rand(Issue::getTypes())])
                ->setSummary($faker->text(50))
                ->setCode(strtoupper($issue->getType()) . '-' . $i)
            ;
            $manager->persist($issue);
        }

        $manager->flush();
    }
}
