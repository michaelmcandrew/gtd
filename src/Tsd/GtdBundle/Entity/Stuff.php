<?php

namespace Tsd\GtdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stuff
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Stuff
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $processed;

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
     * @param string $description
     * @return Stuff
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set processed
     *
     * @param \DateTime $processed
     * @return Stuff
     */
    public function setProcessed($processed)
    {
        $this->processed = $processed;
    
        return $this;
    }

    /**
     * Get processed
     *
     * @return \DateTime 
     */
    public function getProcessed()
    {
        return $this->processed;
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
     * @return Stuff
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
}