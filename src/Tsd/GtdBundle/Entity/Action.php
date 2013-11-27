<?php

namespace Tsd\GtdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Action
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Tsd\GtdBundle\Entity\ActionRepository")
 */
class Action
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $completed;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="actions")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @ORM\ManyToMany(targetEntity="Context", inversedBy="actions")
     */
    private $contexts;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $name
     * @return Action
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Action
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set completed
     *
     * @param \DateTime $completed
     * @return Action
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    
        return $this;
    }

    /**
     * Get completed
     *
     * @return \DateTime 
     */
    public function getCompleted()
    {
        return $this->completed;
    }
    /**
     * @ORM\PrePersist
     */
    public function presetCreated()
    {
        $this->created = new \DateTime();
    }

    /**
     * Set project
     *
     * @param \Tsd\GtdBundle\Entity\Project $project
     * @return Action
     */
    public function setProject(\Tsd\GtdBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \Tsd\GtdBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contexts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add contexts
     *
     * @param \Tsd\GtdBundle\Entity\Context $contexts
     * @return Action
     */
    public function addContext(\Tsd\GtdBundle\Entity\Context $contexts)
    {
        $this->contexts[] = $contexts;
    
        return $this;
    }

    /**
     * Remove contexts
     *
     * @param \Tsd\GtdBundle\Entity\Context $contexts
     */
    public function removeContext(\Tsd\GtdBundle\Entity\Context $contexts)
    {
        $this->contexts->removeElement($contexts);
    }

    /**
     * Get contexts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContexts()
    {
        return $this->contexts;
    }
}