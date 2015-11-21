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
     * @var ranking
     *
     * @ORM\ManyToOne(targetEntity="Ranking", inversedBy="experience")
     * @Assert\NotBlank
     */
    private $ranking;

    /**
     * @var underRanking
     *
     * @ORM\ManyToOne(targetEntity="UnderRanking", inversedBy="experience")
 	 * @ORM\JoinColumn(name="underRanking_id", referencedColumnName="id", nullable=true)
     */
    private $underRanking;

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
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="experience")
 	 * @ORM\JoinColumn(name="role_1_id", referencedColumnName="id", nullable=true)
     */
    private $role_1;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
 	 * @ORM\JoinColumn(name="role_2_id", referencedColumnName="id", nullable=true)
     */
    private $role_2;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
 	 * @ORM\JoinColumn(name="role_3_id", referencedColumnName="id", nullable=true)
     */
    private $role_3;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
 	 * @ORM\JoinColumn(name="role_4_id", referencedColumnName="id", nullable=true)
     */
    private $role_4;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
 	 * @ORM\JoinColumn(name="role_5_id", referencedColumnName="id", nullable=true)
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
     * Set ranking
     *
     * @param \AppBundle\Entity\Ranking $ranking
     * @return Experience
     */
    public function setRanking(\AppBundle\Entity\Ranking $ranking = null)
    {
        $this->ranking = $ranking;

        return $this;
    }

    /**
     * Get ranking
     *
     * @return \AppBundle\Entity\Ranking
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * Set underRanking
     *
     * @param \AppBundle\Entity\UnderRanking $underRanking
     * @return Experience
     */
    public function setUnderRanking(\AppBundle\Entity\UnderRanking $underRanking = null)
    {
        $this->underRanking = $underRanking;

        return $this;
    }

    /**
     * Get underRanking
     *
     * @return \AppBundle\Entity\UnderRanking
     */
    public function getUnderRanking()
    {
        return $this->underRanking;
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

    public function __toString()
    {
      return $this->username;
    }
}
