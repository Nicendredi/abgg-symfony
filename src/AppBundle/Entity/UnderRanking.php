<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use DoctrineCommonCollectionsArrayCollection;

/**
 * UnderRanking
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UnderRankingRepository")
 */
class UnderRanking
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
	 * @var \Doctrine\Common\Collections\Collection|Ranking[]
	 * 
     * @ORM\ManyToMany(targetEntity="Ranking", inversedBy="ranking", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="total_ranking",
     *      joinColumns={@ORM\JoinColumn(name="underRanking_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="ranking_id", referencedColumnName="id")}
     *      )
     */
    private $ranking;

    public function __construct() {
        $this->ranking = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return UnderRanking
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
	
    public function addRanking(\AppBundle\Entity\Ranking $ranking)
    {
        $this->ranking[] = $ranking;

        return $this;
    }
    public function removeRanking(\AppBundle\Entity\Ranking $ranking)
    {
        $this->ranking->removeElement($ranking);
    }
    /**
     * Get underRanking
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRanking()
    {
        return $this->ranking;
    }
}
