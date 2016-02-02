<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;


/**
 * Experience
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ExperienceRepository")
 * @AppAssert\HasDifferentRoles
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="steam", type="string", length=255, nullable=true)
     */
    private $steam;

    /**
     * @var postes
     *
     * @ORM\OneToOne(targetEntity="Postes", cascade={"persist", "remove"})
     */
    private $postes;



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
     * Set steam
     *
     * @param string $steam
     * @return Experience
     */
    public function setSteam($steam)
    {
        $this->steam = $steam;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getSteam()
    {
        return $this->steam;
    }


    /**
     * Set postes
     *
     * @param \AppBundle\Entity\Postes $postes
     * @return Experience
     */
    public function setPostes(Postes $postes)
    {
        $this->postes = $postes;

        return $this;
    }

    /**
     * Get postes
     *
     * @return \AppBundle\Entity\User
     */
    public function getPostes()
    {
        return $this->postes;
    }
	
	
    public function __toString()
    {
      return $this->username;
    }
}
