<?php

namespace AP\Bundle\BugTrackerBundle\Entity;

use AP\Bundle\BugTrackerBundle\Model\ExtendIssue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * Class Issue
 * @package AP\Bundle\BugTrackerBundle
 *
 * @ORM\Entity(repositoryClass="AP\Bundle\BugTrackerBundle\Entity\Repository\IssueRepository")
 * @ORM\Table(
 *      name="ap_bug_tracker_issue"
 * )
 * @ORM\HasLifecycleCallbacks
 *
 * @Config(
 *      routeName="bug_tracker.issue_index",
 *      routeView="bug_tracker.issue_view",
 *      defaultValues={
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "workflow"={"active_workflow"="issue_flow"}
 *      }
 * )
 */
class Issue extends ExtendIssue implements Taggable
{
    const TYPE_STORY = 'story';
    const TYPE_BUG = 'bug';
    const TYPE_SUBTASK = 'subtask';
    const TYPE_TASK = 'task';

    /**
     * @return string[]
     */
    public static function getTypes()
    {
        return [
            static::TYPE_STORY,
            static::TYPE_BUG,
            static::TYPE_SUBTASK,
            static::TYPE_TASK,
        ];
    }

    /**
     * @return int[]
     */
    public static function getSubtaskTypes()
    {
        return [
            static::TYPE_SUBTASK,
        ];
    }

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "order"=10,
     *          "short"=true
     *      }
     *  }
     * )
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "order"=30,
     *          "short"=true
     *      }
     *  }
     * )
     */
    protected $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "order"=90,
     *          "short"=true
     *      }
     *  }
     * )
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, nullable=true)
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "order"=20,
     *          "short"=true
     *      }
     *  }
     * )
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="issue_type", type="string")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "order"=40,
     *          "short"=true
     *      }
     *  }
     * )
     */
    protected $type;

    /**
     * @var Priority
     *
     * @ORM\ManyToOne(targetEntity="Priority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "order"=50
     *      }
     *  }
     * )
     */
    protected $priority;

    /**
     * @var Resolution
     *
     * @ORM\ManyToOne(targetEntity="Resolution")
     * @ORM\JoinColumn(name="resolution_id", referencedColumnName="id")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "order"=60
     *      }
     *  }
     * )
     */
    protected $resolution;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="subtasks")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $parentIssue;

    /**
     * @var ArrayCollection;
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parentIssue")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $subtasks;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "order"=70,
     *          "short"=true
     *      }
     *  }
     * )
     */
    protected $assignee;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "order"=80,
     *          "short"=true
     *      }
     *  }
     * )
     */
    protected $reporter;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(
     *     name="issues_collaborators",
     *     joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $collaborators;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="created_at", type="datetime")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $updatedAt;

    /**
     * @var ArrayCollection*
     *
     * @ConfigField(
     *      defaultValues={
     *          "merge"={
     *              "display"=true
     *          },
     *         "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $tags;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true},
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $workflowStep;

    /**
     * Issue constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->subtasks = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getSummary();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param WorkflowItem $workflowItem
     * @return $this
     */
    public function setWorkflowItem(WorkflowItem $workflowItem)
    {
        $this->workflowItem = $workflowItem;

        return $this;
    }

    /**
     * @return WorkflowItem
     */
    public function getWorkflowItem()
    {
        return $this->workflowItem;
    }

    /**
     * @param WorkflowStep $workflowStep
     * @return $this
     */
    public function setWorkflowStep(WorkflowStep $workflowStep)
    {
        $this->workflowStep = $workflowStep;

        return $this;
    }

    /**
     * @return WorkflowStep
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
    }

    /**
     * @return Issue
     */
    public function getParentIssue()
    {
        return $this->parentIssue;
    }

    /**
     * @param Issue $parentIssue
     * @return $this
     */
    public function setParentIssue(Issue $parentIssue)
    {
        $this->parentIssue = $parentIssue;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubtasks()
    {
        return $this->subtasks;
    }

    /**
     * @param ArrayCollection $subtasks
     * @return $this
     */
    public function setSubtasks($subtasks)
    {
        $this->subtasks = $subtasks;

        return $this;
    }

    /**
     * @return User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @param User $assignee
     * @return $this
     */
    public function setAssignee(User $assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * @return ArrayCollection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * @param ArrayCollection $collaborators
     * @return $this
     */
    public function setCollaborators($collaborators)
    {
        $this->collaborators = $collaborators;

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addCollaborator(User $user)
    {
        if (!$this->collaborators->contains($user)) {
            $this->collaborators->add($user);
        }

        return $this;
    }

    /**
     * @param User $reporter
     * @return $this
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return $this
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        if (!in_array($type, static::getTypes(), true)) {
            throw new \InvalidArgumentException();
        }
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Priority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param Priority $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return Resolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param Resolution $resolution
     * @return $this
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Pre persist event handler
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->preUpdate();
        $this->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
    }

    /**
     * Pre update event handler
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));

        if ($this->getAssignee()) {
            $this->addCollaborator($this->getAssignee());
        }

        if ($this->getReporter()) {
            $this->addCollaborator($this->getReporter());
        }
    }

    /**
     * Returns the unique taggable resource identifier
     *
     * @return string
     */
    public function getTaggableId()
    {
        return $this->getId();
    }


    /**
     * Returns the collection of tags for this Taggable entity
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTags()
    {
        if (!$this->tags) {
            $this->tags = new ArrayCollection();
        }

        return $this->tags;
    }

    /**
     * Set tag collection
     *
     * @param $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }
}
