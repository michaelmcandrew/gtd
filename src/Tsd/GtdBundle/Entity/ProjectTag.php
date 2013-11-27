<?php

namespace Tsd\GtdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectTag
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProjectTag
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
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="projectTags")
     **/
    private $users;

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
     * @return ProjectTag
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
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add users
     *
     * @param \Tsd\GtdBundle\Entity\Project $users
     * @return ProjectTag
     */
    public function addUser(\Tsd\GtdBundle\Entity\Project $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Tsd\GtdBundle\Entity\Project $users
     */
    public function removeUser(\Tsd\GtdBundle\Entity\Project $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function __toString(){
        return $this->name;
    }
}
