<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Entity\Team;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @UniqueEntity("experience")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     * @Assert\NotBlank
     * @AppAssert\IsFrenchPhoneNumber
     */
    private $telephone;

    /**
     * @var birth
     *
     * @ORM\Column(name="birth", type="date")
     * @Assert\NotBlank
     */
    private $birth;

    /**
     * @var tournament
     *
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="user")
     */
    private $tournament;

    /**
     * @var experience
     *
     * @ORM\OneToOne(targetEntity="Experience")
     */
    private $experience;

    /**
     * @var player
     *
     * @ORM\OneToOne(targetEntity="Player", cascade={"persist"})
     */
    private $player;

    /**
     * @var Team $team
     *
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="user", cascade={"persist", "merge"})
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    private $team;

    /**
     * @var Role $role
     *
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="user", cascade={"persist", "merge"})
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;

    /**
     * @var boolean
     *
     * @ORM\Column(name="capitain", type="boolean", nullable=true)
     */
    private $capitain;

    /**
     * @var boolean
     *
     * @ORM\Column(name="manager", type="boolean", nullable=true)
     */
    private $manager;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set birth
     *
     * @param date $birth
     * @return User
     */
    public function setBirth($birth)
    {
        $this->birth = $birth;

        return $this;
    }

    /**
     * Get birth
     *
     * @return string
     */
    public function getBirth()
    {
        return $this->birth;
    }

    /**
     * Set experience
     *
     * @param \AppBundle\Entity\Experience $experience
     * @return User
     */
    public function setExperience(Experience $experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return \AppBundle\Entity\Experience
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     * @return User
     */
    public function setPlayer(Player $player =null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set tournament
     *
     * @param \AppBundle\Entity\Game $tournament
     * @return User
     */
    public function setTournament($tournament = null)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return \AppBundle\Entity\Game
     */
    public function getTournament()
    {
        return $this->tournament;
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
     * Set capitain
     *
     * @param boolean $capitain
     * @return Experience
     */
    public function setCapitain($capitain)
    {
        $this->capitain = $capitain;

        return $this;
    }

    /**
     * Get capitain
     *
     * @return boolean
     */
    public function getCapitain()
    {
        return $this->capitain;
    }

    /**
     * Set manager
     *
     * @param boolean $manager
     * @return Experience
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return boolean
     */
    public function getManager()
    {
        return $this->manager;
    }
}
