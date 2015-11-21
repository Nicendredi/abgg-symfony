<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GameRepository")
 */
class Game
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
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="syst_name", type="string", length=30, unique=true)
     * @Assert\NotBlank
     */
    private $systName;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     * @Assert\NotBlank
     */
    private $image;

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
     * @return Game
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
     * Set systName
     *
     * @param string $systName
     * @return Game
     */
    public function setSystName($systName)
    {
        $this->systName = $systName;

        return $this;
    }

    /**
     * Get systName
     *
     * @return string
     */
    public function getSystName()
    {
        return $this->systName;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Game
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
