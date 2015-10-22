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
            'description' => 'Test properties access method for Issue entity description.',
            'code' => 1,
            'priority' => new Priority(),
            'type' => 1,
            'resolution' => new Resolution(),
            'createdAt' => new \DateTime(),
            'updatedAt' => new \DateTime(),
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

    public function testIssueTags()
    {
        $issue = new Issue();
        $this->assertEquals($issue->getTags(), new ArrayCollection());

        $testCollection = new ArrayCollection([1, 2]);
        $issue->setTags($testCollection);

        $this->assertSame($issue->getTags(), $testCollection);
    }

    public function testToString()
    {
        $issue = new Issue();
        $issue->setSummary('summary');

        $this->assertSame('summary', (string)$issue);
    }

    public function testGetTypes()
    {
        $this->assertEquals(Issue::getTypes(), [1, 2, 3]);
    }

    public function testGetTypeNames()
    {
        $this->assertSame(Issue::getTypeNames(), [
            1 => 'ap.bug_tracker.type.story',
            2 => 'ap.bug_tracker.type.bug',
            3 => 'ap.bug_tracker.type.improvement'
        ]);
    }

    public function testPrePersist()
    {
        $issue = new Issue();
        $issue->prePersist();

        $this->assertInstanceOf('\DateTime', $issue->getUpdatedAt());
        $this->assertInstanceOf('\DateTime', $issue->getCreatedAt());
    }

    public function testPreUpdate()
    {
        $issue = new Issue();
        $initialDate = new \DateTime('-1 month');
        $issue->setUpdatedAt($initialDate);
        $issue->preUpdate();

        $this->assertTrue($issue->getUpdatedAt() > $initialDate);
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
