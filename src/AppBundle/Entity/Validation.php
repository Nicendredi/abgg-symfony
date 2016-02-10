<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Validation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ValidationRepository")
 */
class Validation
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;
	
 	/**
	 * @var Team 
	 * 
     *     *
     * @ORM\OneToOne(targetEntity="Team")

     **/
    private $team;

    /**
     * @var boolean
     *
     * @ORM\Column(name="payed", type="boolean", nullable=true)
     */
    private $payed;


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
     * Set date
     *
     * @param \DateTime $date
     * @return Validation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set team
     *
     * @param Team $team
     * @return Validationn
     */
    public function setTeam($team)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }


    /**
     * Set payed
     *
     * @param boolean $payed
     * @return Validation
     */
    public function setPayed($payed)
    {
        $this->payed = $payed;

        return $this;
    }

    /**
     * Get payed
     *
     * @return boolean
     */
    public function getPayed()
    {
        return $this->payed;
    }
}
