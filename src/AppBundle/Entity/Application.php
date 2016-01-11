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
     * @var Team $team
     *
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="application", cascade={"persist", "merge"})
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    private $team;

    /**
     * @var Role $role
     *
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="application", cascade={"persist", "merge"})
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="application", cascade={"persist", "merge"})
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=255, nullable=true)
     */
    private $origin;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="blocked", type="boolean", nullable=true)
     */
    private $blocked;

	/**
     * @var string
     *
     * @ORM\Column(name="invitationToken", type="string", length=255, nullable=true)
     */
    private $invitationToken;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255, nullable=true)
     */
    private $text;

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
     * @param Team $team
     */
    public function setTeam(Team $team)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Team $team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Role $role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set origin
     *
     * @param string $origin
     * @return Application
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Application
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set blocked
     *
     * @param boolean $blocked
     * @return Application
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
	}
	
    /**
     * Get blocked
     *
     * @return boolean
     */
    public function getBlocked()
    {
        return $this->blocked;
	}

	/**
     * Set invitationToken
     *
     * @param string $invitationToken
     * @return Application
     */
    public function setInvitationToken($invitationToken)
    {
        $this->invitationToken = $invitationToken;

        return $this;
    }
		
	/**
     * Get invitationToken
     *
     * @return string 
     */
    public function getInvitationToken()
    {
        return $this->invitationToken;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Application
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
