<?php

namespace AP\Bundle\BugTrackerBundle\Tests\Unit\Entity;

use AP\Bundle\BugTrackerBundle\Entity\Priority;
use AP\Component\TestUtils\Entity\EntityTestCaseTrait;

class PriorityTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestCaseTrait;

    /**
     * @var array
     */
    protected static $testData = [
        'id' => 1,
        'name' => 'low',
        'label' => 'Low',
        'order' => '1',
        'description' => 'Minor problem.'
    ];

    public function testEntityGetters()
    {
        $entity = $this->getEntity(self::$testData);

        $this->assertEntityGetters($entity, self::$testData);
    }

    public function testEntitySetters()
    {
        $testData = self::$testData;
        unset($testData['id']);

        $this->assertEntitySetters(new Priority(), $testData);
    }

    public function testToString()
    {
        $entity = $this->getEntity(self::$testData);

        $this->assertSame('Low', (string) $entity);
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
