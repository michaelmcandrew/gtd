<?php

namespace Tsd\GtdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Context
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Context
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToMany(targetEntity="Action", mappedBy="contexts")
     */
    private $actions;


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
     * @return Context
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
     * Set actions
     *
     * @param \stdClass $actions
     * @return Context
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    
        return $this;
    }

    /**
     * Get actions
     *
     * @return \stdClass 
     */
    public function getActions()
    {
        return $this->actions;
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
     * @param \Tsd\GtdBundle\Entity\Action $actions
     * @return Context
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

    public function __toString(){
        return $this->name;
    }
}