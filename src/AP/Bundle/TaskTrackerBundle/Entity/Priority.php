<?php

namespace AP\Bundle\TaskTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Priority
 * @package AP\Bundle\TaskTrackerBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="ap_task_tracker_priority")
 */
class Priority
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority_order", type="integer")
     */
    protected $order;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }
}
