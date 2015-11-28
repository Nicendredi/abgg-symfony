<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * Role
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RoleRepository")
 */
class Role
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
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var game
     *
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(nullable=true)
     */
     private $game;

 	/**
	 * @var \Doctrine\Common\Collections\Collection|Player[]
	 * 
     *
     * @ORM\OnetoMany(targetEntity="Player", mappedBy="role",cascade={"persist"})
     **/
    private $player;

 	/**
	 * @var \Doctrine\Common\Collections\Collection|Application[]
	 * 
     *
     * @ORM\OnetoMany(targetEntity="Application", mappedBy="role",cascade={"persist"})
     **/
    private $application;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->player = new \Doctrine\Common\Collections\ArrayCollection();
        $this->application = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Role
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
     * Set game
     *
     * @param \AppBundle\Entity\Game $game
     * @return Role
     */
    public function setGame(\AppBundle\Entity\Game $game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \AppBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
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
        $application->setRole($this);
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
 

    public function __toString()
    {
      return $this->name;
    }
}
