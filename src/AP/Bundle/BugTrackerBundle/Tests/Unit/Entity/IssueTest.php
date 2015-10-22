<?php


namespace AP\Bundle\BugTrackerBundle\Tests\Unit\Entity;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use AP\Bundle\BugTrackerBundle\Entity\Priority;
use AP\Bundle\BugTrackerBundle\Entity\Resolution;
use AP\Component\TestUtils\Entity\EntityTestCaseTrait;
use Doctrine\Common\Collections\ArrayCollection;

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
            'updatedAt' => new \DateTime(),
            'tags' => new ArrayCollection()
        ];
    }

    public function testIssueImplementsTaggableInterface()
    {
        $this->assertInstanceOf('Oro\Bundle\TagBundle\Entity\Taggable', new Issue());
    }

    public function testIssueGetters()
    {
        $testData = array_merge(['id' => 1], $this->entityTestData);
        $entity = $this->createEntity($testData);

        $this->assertEntityGetters($entity, $testData);
    }

    public function testGetTaggableId()
    {
        $issue = $this->createEntity(array_merge(['id' => 1], $this->entityTestData));
        $this->assertEquals(1, $issue->getTaggableId());
    }

    public function testIssueSetters()
    {
        $this->assertEntitySetters(new Issue(), $this->entityTestData);
    }

    /**
     * @param array $entityData
     * @return Issue
     */
    private function createEntity(array $entityData)
    {
        $entity = new Issue();

        return $this->setEntityData($entity, $entityData);
    }
}
