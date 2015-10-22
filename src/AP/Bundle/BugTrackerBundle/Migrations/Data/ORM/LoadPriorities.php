<?php


namespace AP\Bundle\BugTrackerBundle\Migrations\Data\ORM;

use AP\Bundle\BugTrackerBundle\Entity\Priority;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPriorities extends AbstractFixture
{
    /**
     * @var array
     */
    protected static $data = [
        [
            'name' => 'ap.bug_tracker.priority.high',
            'description' => 'Serious problem that could block progress.',
            'order' => 3
        ],
        [
            'name' => 'ap.bug_tracker.priority.medium',
            'description' => 'Has a problem to affect progress.',
            'order' => 2
        ],
        [
            'name' => 'ap.bug_tracker.priority.low',
            'description' => 'Minor problem or easily worked around.',
            'order' => 1
        ]
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (static::$data as $priorityData) {
            $entity = new Priority();
            $entity->setName($priorityData['name'])
                ->setDescription($priorityData['description'])
                ->setOrder($priorityData['order']);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
