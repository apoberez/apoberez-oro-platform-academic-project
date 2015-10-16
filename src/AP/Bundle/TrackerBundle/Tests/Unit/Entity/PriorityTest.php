<?php

namespace AP\Bundle\TrackerBundle\Tests\Unit\Entity;

use AP\Bundle\TrackerBundle\Entity\Priority;
use AP\Component\TestUtils\Entity\EntityTestCaseTrait;

class PriorityTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestCaseTrait;


    public function testProperties()
    {
        $testData = [
            'id' => 1,
            'name' => 'Low',
            'order' => '1',
            'description' => 'Minor problem.'
        ];

        $entity = $this->getEntity($testData);

        $this->assertEntityGetters($entity, $testData);
    }

    /**
     * @param array $data
     * @return Priority
     */
    private function getEntity(array $data)
    {
        $entity = new Priority();
        return self::setEntityData($entity, $data);
    }
}
