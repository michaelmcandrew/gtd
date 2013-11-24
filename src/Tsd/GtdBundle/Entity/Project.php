<?php
namespace Tsd\GtdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */

class Project
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    
    /**
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $completed;
 
    /**
     * @ORM\ManyToOne(targetEntity="Timeframe", inversedBy="projects")
     * @ORM\JoinColumn(name="timeframe_id", referencedColumnName="id", nullable=false)
     */
    protected $timeframe;

    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="project")
     */
    protected $actions;

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
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @ORM\PrePersist
     */
    public function presetCreated()
    {
        $this->created = new \DateTime();
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Project
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
     * @return Project
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
     * Constructor
     */
    public function __construct()
    {
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add actions
     *
     * @param \Tsd\GtdBundle\Enttity\Action $actions
     * @return Project
     */
    public function addAction(\Tsd\GtdBundle\Entity\Action $actions)
    {
        $this->actions[] = $actions;
    
        return $this;
    }

    /**
     * Remove actions
     *
     * @param \Tsd\GtdBundle\Entity\Action $actions
     */
    public function removeAction(\Tsd\GtdBundle\Entity\Action $actions)
    {
        $this->actions->removeElement($actions);
    }

    /**
     * Get actions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActions()
    {
        return $this->actions;
    }

    public function __toString(){
        return $this->name;
    }

    /**
     * Set timeframe
     *
     * @param string $timeframe
     * @return Project
     */
    public function setTimeframe($timeframe)
    {
        $this->timeframe = $timeframe;
    
        return $this;
    }

    /**
     * Get timeframe
     *
     * @return string 
     */
    public function getTimeframe()
    {
        return $this->timeframe;
    }
}
