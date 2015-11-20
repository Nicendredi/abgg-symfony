<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use DoctrineCommonCollectionsArrayCollection;

/**
 * Ranking
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RankingRepository")
 */
class Ranking
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
     * @var game
     *
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
     private $game;

 	/**
	 * @var \Doctrine\Common\Collections\Collection|UnderRanking[]
	 *
     * @ORM\ManyToMany(targetEntity="UnderRanking", mappedBy="ranking")
     */
    private $underRanking;

    public function __construct() 
    {
        $this->underRanking = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Ranking
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
     * @return Ranking
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
	
    public function addUnderRanking(\AppBundle\Entity\UnderRanking $underRanking)
    {
        $underRanking->setRanking($this);
        $this->underRanking[] = $underRanking;

        return $this;
    }
    public function removeUnderRanking(\AppBundle\Entity\UnderRanking $underRanking)
    {
        $this->underRanking->removeElement($underRanking);
    }
    /**
     * Get underRanking
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnderRanking()
    {
        return $this->underRanking;
    }
}
