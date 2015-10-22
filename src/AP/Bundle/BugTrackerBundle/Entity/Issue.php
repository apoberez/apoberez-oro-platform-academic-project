<?php

namespace AP\Bundle\BugTrackerBundle\Entity;

use AP\Bundle\BugTrackerBundle\Model\ExtendIssue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\TagBundle\Entity\Taggable;

/**
 * Class Issue
 * @package AP\Bundle\BugTrackerBundle
 *
 * @ORM\Entity
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
 *          }
 *      }
 * )
 */
class Issue extends ExtendIssue implements Taggable
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $code;

    /**
     * @var Priority
     *
     * @ORM\ManyToOne(targetEntity="Priority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     */
    protected $priority;

    /**
     * @var Resolution
     *
     * @ORM\ManyToOne(targetEntity="Resolution")
     * @ORM\JoinColumn(name="resolution_id", referencedColumnName="id")
     */
    protected $resolution;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var ArrayCollection*
     *
     * @ConfigField(
     *      defaultValues={
     *          "merge"={
     *              "display"=true
     *          }
     *      }
     * )
     */
    protected $tags;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
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
    public function setUpdatedAt($updatedAt = null)
    {
        $updatedAt = $updatedAt ?: new \DateTime('now', new \DateTimeZone('UTC'));
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
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * Pre update event handler
     *todo fix
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
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
     * @return string
     */
    public function __toString()
    {
        return $this->getSummary();
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
