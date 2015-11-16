<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Team
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
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User")
     */
    private $captain;

    /**
    * @var User
    *
    * @ORM\OnetoOne(targetEntity="User")
    **/
    private $post1;

    /**
    * @var User
    *
    * @ORM\OnetoOne(targetEntity="User")
    **/
    private $post2;

    /**
    * @var User
    *
    * @ORM\OnetoOne(targetEntity="User")
    **/
    private $post3;

    /**
    * @var User
    *
    * @ORM\OnetoOne(targetEntity="User")
    **/
    private $post4;

    /**
    * @var User
    *
    * @ORM\OnetoOne(targetEntity="User")
    **/
    private $post5;

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
     * @return Team
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
     * Set captain
     *
     * @param User $captain
     * @return Team
     */
    public function setCaptain($captain)
    {
        $this->captain = $captain;

        return $this;
    }

    /**
     * Get captain
     *
     * @return User
     */
    public function getCaptain()
    {
        return $this->captain;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set post1
     *
     * @param \AppBundle\Entity\User $post1
     * @return Team
     */
    public function setPost1(\AppBundle\Entity\User $post1 = null)
    {
        $this->post1 = $post1;

        return $this;
    }

    /**
     * Get post1
     *
     * @return \AppBundle\Entity\User
     */
    public function getPost1()
    {
        return $this->post1;
    }

    /**
     * Set post2
     *
     * @param \AppBundle\Entity\User $post2
     * @return Team
     */
    public function setPost2(\AppBundle\Entity\User $post2 = null)
    {
        $this->post2 = $post2;

        return $this;
    }

    /**
     * Get post2
     *
     * @return \AppBundle\Entity\User
     */
    public function getPost2()
    {
        return $this->post2;
    }

    /**
     * Set post3
     *
     * @param \AppBundle\Entity\User $post3
     * @return Team
     */
    public function setPost3(\AppBundle\Entity\User $post3 = null)
    {
        $this->post3 = $post3;

        return $this;
    }

    /**
     * Get post3
     *
     * @return \AppBundle\Entity\User
     */
    public function getPost3()
    {
        return $this->post3;
    }

    /**
     * Set post4
     *
     * @param \AppBundle\Entity\User $post4
     * @return Team
     */
    public function setPost4(\AppBundle\Entity\User $post4 = null)
    {
        $this->post4 = $post4;

        return $this;
    }

    /**
     * Get post4
     *
     * @return \AppBundle\Entity\User
     */
    public function getPost4()
    {
        return $this->post4;
    }

    /**
     * Set post5
     *
     * @param \AppBundle\Entity\User $post5
     * @return Team
     */
    public function setPost5(\AppBundle\Entity\User $post5 = null)
    {
        $this->post5 = $post5;

        return $this;
    }

    /**
     * Get post5
     *
     * @return \AppBundle\Entity\User
     */
    public function getPost5()
    {
        return $this->post5;
    }

    /**
     * Set tournament
     *
     * @param \AppBundle\Entity\Tournament $tournament
     * @return Team
     */
    public function setTournament(\AppBundle\Entity\Tournament $tournament = null)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return \AppBundle\Entity\Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    public function getNb()
    {
      $compt = 0;
      if ($this->post1 !== null) {
        $compt++;
      }
      if ($this->post2 !== null) {
        $compt++;
      }
      if ($this->post3 !== null) {
        $compt++;
      }
      if ($this->post4 !== null) {
        $compt++;
      }
      if ($this->post5 !== null) {
        $compt++;
      }
      return $compt;
    }

    public function getPosts()
    {
      $posts[] = null;
      if ($this->post1 == null) {
        $posts[] = "top";
      }
      if ($this->post2 == null) {
        $posts[] = "mid";
      }
      if ($this->post3 == null) {
        $posts[] = "bot";
      }
      if ($this->post4 == null) {
        $posts[] = "support";
      }
      if ($this->post5 == null) {
        $posts[] = "jungler";
      }
      return $posts;
    }

    public function __toString()
    {
      return $this->name;
    }
}
