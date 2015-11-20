<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ApplicationRepository")
 */
class Application
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
	 * @var team
	 *
     * @ORM\ManyToOne(targetEntity="Team")
     */
    private $team;

 	/**
	 * @var role
	 *
     * @ORM\ManyToOne(targetEntity="Role")
     */
    private $role;

 	/**
	 * @var \Doctrine\Common\Collections\Collection|User[]
	 *
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    public function __construct() 
    {
        $this->team = new \Doctrine\Common\Collections\ArrayCollection();
        $this->role = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set team
     *
     * @param string $team
     * @return Application
     */
    public function setTeam($team)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return string
     */
    public function getTeam()
    {
        return $this->team;
    }
	
	/**
     * Set role
     *
     * @param string $role
     * @return Application
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
	
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }
    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }
}
