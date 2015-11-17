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
     * @var boolean
     *
     * @ORM\Column(name="looking_for_team", type="boolean")
     */
    private $lookingForTeam;

    /**
     * @var string
     *
     * @ORM\Column(name="rank_actual", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Choice(callback = "getLolRanks")

     */
    private $rankActual;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @Assert\NotBlank
     */
    private $role_1;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @Assert\NotBlank
     */
    private $role_2;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @Assert\NotBlank
     */
    private $role_3;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @Assert\NotBlank
     */
    private $role_4;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @Assert\NotBlank
     */
    private $role_5;


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
     * Set role_1
     *
     * @param \AppBundle\Entity\Role $role1
     * @return Experience
     */
    public function setRole1(\AppBundle\Entity\Role $role1 = null)
    {
        $this->role_1 = $role1;

        return $this;
    }

    /**
     * Get role_1
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole1()
    {
        return $this->role_1;
    }

    /**
     * Set role_2
     *
     * @param \AppBundle\Entity\Role $role2
     * @return Experience
     */
    public function setRole2(\AppBundle\Entity\Role $role2 = null)
    {
        $this->role_2 = $role2;

        return $this;
    }

    /**
     * Get role_2
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole2()
    {
        return $this->role_2;
    }

    /**
     * Set role_3
     *
     * @param \AppBundle\Entity\Role $role3
     * @return Experience
     */
    public function setRole3(\AppBundle\Entity\Role $role3 = null)
    {
        $this->role_3 = $role3;

        return $this;
    }

    /**
     * Get role_3
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole3()
    {
        return $this->role_3;
    }

    /**
     * Set role_4
     *
     * @param \AppBundle\Entity\Role $role4
     * @return Experience
     */
    public function setRole4(\AppBundle\Entity\Role $role4 = null)
    {
        $this->role_4 = $role4;

        return $this;
    }

    /**
     * Get role_4
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole4()
    {
        return $this->role_4;
    }

    /**
     * Set role_5
     *
     * @param \AppBundle\Entity\Role $role5
     * @return Experience
     */
    public function setRole5(\AppBundle\Entity\Role $role5 = null)
    {
        $this->role_5 = $role5;

        return $this;
    }

    /**
     * Get role_5
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole5()
    {
        return $this->role_5;
    }

    public static function getLolRanks()
    {
      return array('Bronze I', 'Bronze II', 'Bronze III', 'Bronze IV', 'Bronze V', );
    }

    public function __toString()
    {
      return $this->username;
    }
}
