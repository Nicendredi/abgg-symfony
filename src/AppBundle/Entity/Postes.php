<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * Postes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PostesRepository")
 */
class Postes
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
     * @var integer
     *
     * @ORM\Column(name="top", type="integer")
     */
    private $top;

    /**
     * @var integer
     *
     * @ORM\Column(name="mid", type="integer")
     */
    private $mid;

    /**
     * @var integer
     *
     * @ORM\Column(name="bot", type="integer")
     */
    private $bot;

    /**
     * @var integer
     *
     * @ORM\Column(name="sup", type="integer")
     */
    private $sup;

    /**
     * @var integer
     *
     * @ORM\Column(name="jungle", type="integer")
     */
    private $jungle;


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
     * Set top
     *
     * @param integer $top
     * @return Postes
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Get top
     *
     * @return integer 
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set mid
     *
     * @param integer $mid
     * @return Postes
     */
    public function setMid($mid)
    {
        $this->mid = $mid;

        return $this;
    }

    /**
     * Get mid
     *
     * @return integer 
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * Set bot
     *
     * @param integer $bot
     * @return Postes
     */
    public function setBot($bot)
    {
        $this->bot = $bot;

        return $this;
    }

    /**
     * Get bot
     *
     * @return integer 
     */
    public function getBot()
    {
        return $this->bot;
    }

    /**
     * Set sup
     *
     * @param integer $sup
     * @return Postes
     */
    public function setSup($sup)
    {
        $this->sup = $sup;

        return $this;
    }

    /**
     * Get sup
     *
     * @return integer 
     */
    public function getSup()
    {
        return $this->sup;
    }

    /**
     * Set jungle
     *
     * @param integer $jungle
     * @return Postes
     */
    public function setJungle($jungle)
    {
        $this->jungle = $jungle;

        return $this;
    }

    /**
     * Get jungle
     *
     * @return integer 
     */
    public function getJungle()
    {
        return $this->jungle;
    }

}
