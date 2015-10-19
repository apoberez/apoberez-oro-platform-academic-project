<?php


namespace AP\Bundle\TaskTrackerBundle\Migrations\Data\ORM;

use AP\Bundle\TaskTrackerBundle\Entity\Resolution;
use AP\Component\ObjectAccessUtils\ObjectPrivatePropertiesSetter;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadResolutions extends AbstractFixture
{
    use ObjectPrivatePropertiesSetter;

    /**
     * @var array
     */
    protected static $data = [
        [
            'name' => 'Fixed',
            'description' => 'A fix for this issue is checked in tree and tested.',
            'order' => 1
        ],
        [
            'name' => 'Duplicate',
            'description' => 'The problem is duplicate of existing issue.',
            'order' => 2
        ],
        [
            'name' => 'Will_not_fix',
            'description' => 'The problem described is an issue which will never be fixed.',
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
        foreach (static::$data as $resolutionData) {
            $entity = new Resolution();
            $this->setObjectProperties($entity, $resolutionData);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
