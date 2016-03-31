<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * Team
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TeamRepository")
 * @AppAssert\HasDifferentPlayers
 */
class Team
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
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User")
     */
    private $captain;

 	/**
	 * @var \Doctrine\Common\Collections\Collection|Player[]
	 * 
     *
     * @ORM\OneToMany(targetEntity="Player", mappedBy="team",cascade={"persist", "remove"})
     **/
    private $player;

 	/**
	 * @var \Doctrine\Common\Collections\Collection|Application[]
	 * 
     *
     * @ORM\OneToMany(targetEntity="Application", mappedBy="team",cascade={"persist", "remove"})
     **/
    private $application;


    /**
     * @var Validation
     *
     * @ORM\OneToOne(targetEntity="Validation")
     */
    private $validation;

 	/**
	 * @var tournament
	 * 
     *
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="team")
     **/
    private $tournament;

    /**
     * @var Image $image
     *
     * @ORM\ManyToOne(targetEntity="Image", inversedBy="image", cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * })
     */
    private $image;

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
     * @return Team
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
     * Set captain
     *
     * @param User $captain
     * @return Team
     */
    public function setCaptain($captain)
    {
        $this->captain = $captain;

        return $this;
    }

    /**
     * Get captain
     *
     * @return User
     */
    public function getCaptain()
    {
        return $this->captain;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->player = new \Doctrine\Common\Collections\ArrayCollection();
        $this->application = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tournament
     *
     * @param \AppBundle\Entity\Game $tournament
     * @return Team
     */
    public function setTournament(\AppBundle\Entity\Game $tournament = null)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return \AppBundle\Entity\Team
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    public function __toString()
    {
      return $this->name;
    }
	
 
    /**
     * @param Player $player
     */
    public function addPlayer(\AppBundle\Entity\Player $player)
    {
        $player->setTeam($this);
        $this->player[] = $player;
    }
	
    /**
     * @return ArrayCollection $player
     */
    public function getPlayer()
    {
        return $this->player;
    }
	
    public function removePlayer(\AppBundle\Entity\Player $player)
    {
        $this->player->removeElement($player);
    }
	
 
    /**
     * @param Application $application
     */
    public function addApplication(\AppBundle\Entity\Application $application)
    {
        $application->setTeam($this);
        $this->application[] = $application;
    }
	
    /**
     * @return ArrayCollection $application
     */
    public function getApplication()
    {
        return $this->application;
    }
	
    public function removeApplication(\AppBundle\Entity\Application $application)
    {
        $this->application->removeElement($application);
    }
	
    /**
     * Set validation
     *
     * @param Validation $validation
     * @return Team
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Get validation
     *
     * @return Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @param Image $image
     */
    public function setImage(Image $image=null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Image $image
     */
    public function getImage()
    {
        return $this->image;
    }
}
