<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table()
 * @ORM\Entity
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
     * @ORM\OnetoMany(targetEntity="Player", mappedBy="team",cascade={"persist", "remove"})
     **/
    private $player;

 	/**
	 * @var tournament
	 * 
     *
     * @ORM\ManytoOne(targetEntity="Game", inversedBy="team")
     **/
    private $tournament;
	
    /**
     * @var validation
     *
     * @ORM\Column(name="validation", type="datetime", nullable=true)
     */
    private $validation;

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
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set validation
     *
     * @param datetime $validation
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
     * @return string
     */
    public function getValidation()
    {
        return $this->validation;
    }
	
 
    /**
     * @param Player $player
     */
    public function addPlayer(\AppBundle\Entity\Player $player)
    {
        $player->setTeam($this);
		
		if (!$this->player->contains($player)) {
            $this->player->add($player);
        }
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
     * Set game
     *
     * @param \AppBundle\Entity\Game $game
     * @return Team
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
}
