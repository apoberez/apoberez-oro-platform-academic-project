<?php

namespace AP\Bundle\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Issue
 * @package AP\Bundle\TrackerBundle
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="ap_tracker_issue"
 * )
 */
class Issue
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
     * @var integer
     *
     * @ORM\Column(type="integer")
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
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
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
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
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
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
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
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
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
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
