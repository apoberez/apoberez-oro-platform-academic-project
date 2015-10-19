<?php


namespace AP\Bundle\TrackerBundle\Tests\Unit\Entity;

use AP\Bundle\TrackerBundle\Entity\Issue;
use AP\Bundle\TrackerBundle\Entity\Priority;
use AP\Bundle\TrackerBundle\Entity\Resolution;
use AP\Component\TestUtils\Entity\EntityTestCaseTrait;

class IssueTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestCaseTrait;

    /**
     * @var array
     */
    private $entityTestData = [];

    public function setUp()
    {
        $this->entityTestData = [
            'summary' => 'Test properties access method for Issue entity.',
            'code' => 1,
            'priority' => new Priority(),
            'resolution' => new Resolution(),
            'createdAt' => new \DateTime(),
            'updatedAt' => new \DateTime()
        ];
    }

    public function testIssueGetters()
    {
        $testData = array_merge(['id' => 1], $this->entityTestData);
        $entity = $this->getEntity($testData);

        $this->assertEntityGetters($entity, $testData);
    }

    public function testIssueSetters()
    {
        $this->assertEntitySetters(new Issue(), $this->entityTestData);
    }

    /**
     * @param array $entityData
     * @return Issue
     */
    private function getEntity(array $entityData)
    {
        $entity = new Issue();

        return $this->setEntityData($entity, $entityData);
    }
}
