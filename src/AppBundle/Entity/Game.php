<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GameRepository")
 */
class Game
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
     * @var integer
     *
     * @ORM\Column(name="nb_players", type="integer")
     */
    private $nbPlayers;

    /**
     * @var array
     *
     * @ORM\Column(name="game_roles", type="array")
     */
    private $gameRoles;

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
     * @return Game
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
     * Set nbPlayers
     *
     * @param integer $nbPlayers
     * @return Game
     */
    public function setNbPlayers($nbPlayers)
    {
        $this->nbPlayers = $nbPlayers;

        return $this;
    }

    /**
     * Get nbPlayers
     *
     * @return integer 
     */
    public function getNbPlayers()
    {
        return $this->nbPlayers;
    }

    /**
     * Set gameRoles
     *
     * @param array $gameRoles
     * @return Game
     */
    public function setGameRoles($gameRoles)
    {
        $this->gameRoles = $gameRoles;

        return $this;
    }

    /**
     * Get gameRoles
     *
     * @return array 
     */
    public function getGameRoles()
    {
        return $this->gameRoles;
    }

    /**
     * Add a gameRole
     *
     * @param string $gameRoles
     * @return Game
     */
    public function addGameRoles($gameRole)
    {
        $this->gameRoles->add($gameRole);

        return $this;
    }

    /**
     * Remove a gameRole
     *
     * @return Game 
     */
    public function removeGameRoles($gameRole)
    {
        $this->gameRoles->remove($gameRole);
        return $this;
    }
}
