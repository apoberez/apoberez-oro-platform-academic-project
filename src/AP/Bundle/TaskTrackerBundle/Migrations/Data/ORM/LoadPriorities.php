<?php


namespace AP\Bundle\TaskTrackerBundle\Migrations\Data\ORM;

use AP\Bundle\TaskTrackerBundle\Entity\Priority;
use AP\Component\ObjectAccessUtils\ObjectPrivatePropertiesSetter;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPriorities extends AbstractFixture
{
    use ObjectPrivatePropertiesSetter;

    /**
     * @var array
     */
    protected static $data = [
        [
            'name' => 'high',
            'description' => 'Serious problem that could block progress.',
            'order' => 1
        ],
        [
            'name' => 'medium',
            'description' => 'Has a problem to affect progress.',
            'order' => 2
        ],
        [
            'name' => 'low',
            'description' => 'Minor problem or easily worked around.',
            'order' => 3
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
            $this->setObjectProperties($entity, $priorityData);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
