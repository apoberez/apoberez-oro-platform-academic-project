<?php

namespace AP\Bundle\BugTrackerBundle\Tests\Unit\Entity;

use AP\Bundle\BugTrackerBundle\Entity\Resolution;
use AP\Component\TestUtils\Entity\EntityTestCaseTrait;

class ResolutionTest extends \PHPUnit_Framework_TestCase
{

    use EntityTestCaseTrait;

    /**
     * @var array
     */
    protected static $testData = [
        'id' => 1,
        'name' => 'fixed',
        'label' => 'Fixed',
        'order' => '1',
        'description' => 'Issue fixed.'
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

        $this->assertEntitySetters(new Resolution(), $testData);
    }

    public function testToString()
    {
        $entity = $this->getEntity(self::$testData);

        $this->assertSame('Fixed', (string) $entity);
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
