<?php

namespace Tsd\GtdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tickler
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Tickler
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
     * @ORM\Column(name="due", type="date")
     */
    private $due;


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
     * @return Tickler
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
     * Set due
     *
     * @param \DateTime $due
     * @return Tickler
     */
    public function setDue($due)
    {
        $this->due = $due;
    
        return $this;
    }

    /**
     * Get due
     *
     * @return \DateTime 
     */
    public function getDue()
    {
        return $this->due;
    }
}