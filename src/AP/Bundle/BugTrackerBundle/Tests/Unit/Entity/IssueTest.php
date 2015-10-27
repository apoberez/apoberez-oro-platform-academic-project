<?php


namespace AP\Bundle\BugTrackerBundle\Tests\Unit\Entity;

use AP\Bundle\BugTrackerBundle\Entity\Issue;
use AP\Bundle\BugTrackerBundle\Entity\Priority;
use AP\Bundle\BugTrackerBundle\Entity\Resolution;
use AP\Component\TestUtils\Entity\EntityTestCaseTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

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
            'code' => 'TASK-1',
            'priority' => new Priority(),
            'type' => 'story',
            'resolution' => new Resolution(),
            'parentIssue' => new Issue(),
            'createdAt' => new \DateTime(),
            'subtasks' => new ArrayCollection(),
            'updatedAt' => new \DateTime(),
            'assignee' => new User(),
            'reporter' => new User(),
            'collaborators' => new ArrayCollection(),
            'tags' => new ArrayCollection(),
            'workflowItem' => new WorkflowItem(),
            'workflowStep' => new WorkflowStep()
        ];
    }

    public function testIssueImplementsTaggableInterface()
    {
        $this->assertInstanceOf('Oro\Bundle\TagBundle\Entity\Taggable', new Issue());
    }

    public function testIssueGetters()
    {
        $testData = array_merge([
            'id' => 1
        ], $this->entityTestData);
        $entity = $this->createEntity($testData);

        $this->assertEntityGetters($entity, $testData);
    }

    public function testIssueSetters()
    {
        $this->assertEntitySetters(new Issue(), $this->entityTestData);
    }

    public function testIssueTags()
    {
        $issue = new Issue();
        $this->assertEquals($issue->getTags(), new ArrayCollection());

        $testCollection = new ArrayCollection([1, 2]);
        $issue->setTags($testCollection);

        $this->assertSame($issue->getTags(), $testCollection);
    }

    public function testAddCollaborator()
    {
        $entity = $this->createEntity($this->entityTestData);
        $collaborator1 = new User();
        $collaborator2 = new User();

        $entity->addCollaborator($collaborator1);
        $addResult = $entity->addCollaborator($collaborator2);

        $this->assertSame($addResult, $entity);

        $this->assertSame(2, $entity->getCollaborators()->count());
    }

    public function testCollaboratorCanNotBeRepeated()
    {
        $entity = $this->createEntity($this->entityTestData);
        $collaborator = new User();

        $entity->addCollaborator($collaborator);
        $entity->addCollaborator($collaborator);

        $this->assertSame(1, $entity->getCollaborators()->count());
    }

    public function testToString()
    {
        $issue = new Issue();
        $issue->setSummary('summary');

        $this->assertSame('summary', (string)$issue);
    }

    public function testGetTypes()
    {
        $this->assertEquals(Issue::getTypes(), [
            'story',
            'bug',
            'subtask',
            'task'
        ]);
    }

    public function testGetSubtaskTypes()
    {
        $this->assertEquals(Issue::getSubtaskTypes(), [
            'subtask',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetTypeValidation()
    {
        $entity = new Issue();
        $entity->setType('invalid_type');
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

    public function testCollaboratorsAreAddedOnPreUpdate()
    {
        $issue = new Issue();
        $assignee = new User();
        $reporter = new User();

        $issue->setAssignee($assignee)
            ->setReporter($reporter);

        $issue->preUpdate();

        $this->assertTrue($issue->getCollaborators()->contains($assignee));
        $this->assertTrue($issue->getCollaborators()->contains($reporter));
    }

    public function testGetTaggableId()
    {
        $issue = $this->createEntity(array_merge(['id' => 1], $this->entityTestData));
        $this->assertEquals(1, $issue->getTaggableId());
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
