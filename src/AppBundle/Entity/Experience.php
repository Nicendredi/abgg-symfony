<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Experience
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ExperienceRepository")
 */
class Experience
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
     * @var boolean
     *
     * @ORM\Column(name="looking_for_team", type="boolean")
     */
    private $lookingForTeam;

    /**
     * @var string
     *
     * @ORM\Column(name="rank_actual", type="string", length=255, nullable=true)
     */
    private $rankActual;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var array
     *
     * @ORM\Column(name="positions_sorted_by_favorites", type="array", nullable=true)
     */
    private $positionsSortedByFavorites;


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
     * Set lookingForTeam
     *
     * @param boolean $lookingForTeam
     * @return Experience
     */
    public function setLookingForTeam($lookingForTeam)
    {
        $this->lookingForTeam = $lookingForTeam;

        return $this;
    }

    /**
     * Get lookingForTeam
     *
     * @return boolean 
     */
    public function getLookingForTeam()
    {
        return $this->lookingForTeam;
    }

    /**
     * Set rankActual
     *
     * @param string $rankActual
     * @return Experience
     */
    public function setRankActual($rankActual)
    {
        $this->rankActual = $rankActual;

        return $this;
    }

    /**
     * Get rankActual
     *
     * @return string 
     */
    public function getRankActual()
    {
        return $this->rankActual;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Experience
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set positionsSortedByFavorites
     *
     * @param array $positionsSortedByFavorites
     * @return Experience
     */
    public function setPositionsSortedByFavorites($positionsSortedByFavorites)
    {
        $this->positionsSortedByFavorites = $positionsSortedByFavorites;

        return $this;
    }

    /**
     * Get positionsSortedByFavorites
     *
     * @return array 
     */
    public function getPositionsSortedByFavorites()
    {
        return $this->positionsSortedByFavorites;
    }
}
