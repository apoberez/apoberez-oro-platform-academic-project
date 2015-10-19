<?php

namespace AP\Bundle\TaskTrackerBundle\Tests\Unit\Entity;

use AP\Bundle\TaskTrackerBundle\Entity\Resolution;
use AP\Component\TestUtils\Entity\EntityTestCaseTrait;

class ResolutionTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestCaseTrait;


    public function testProperties()
    {
        $testData = [
            'id' => 1,
            'name' => 'Incomplete',
            'order' => '4',
            'description' => 'The problem is not completely described.'
        ];

        $entity = $this->getEntity($testData);

        $this->assertEntityGetters($entity, $testData);
    }

    /**
     * @param array $data
     * @return Resolution
     */
    private function getEntity(array $data)
    {
        $entity = new Resolution();
        return self::setEntityData($entity, $data);
    }
}
