<?php

namespace Pickl\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rank
 *
 * @ORM\Table(name="rank")
 * @ORM\Entity(repositoryClass="Pickl\AppBundle\Repository\RankRepository")
 */
class Rank
{
    /**
     * @var int
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
     * @Assert\Length(min=5, minMessage="This value is too short. It should have {{ limit }}")
     * @Assert\Length(max=30, maxMessage="This value is too long. It should have {{ limit }}")
     * @Assert\Type(type="string", message="This value is should be a valid string")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="min_level", type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="integer", message="The value {{ value }} should be a valid number")
     * @Assert\Range(
     *     min = 1,
     *     minMessage = "Rank should have a least level {{ limit }}"
     * )
     *
     */
    private $minLevel = 0;

    /**
     * @ORM\OneToMany(targetEntity="Pickl\AppBundle\Entity\User", mappedBy="rank")
     */
    private $users;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return string
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
     * Set minLevel
     *
     * @param integer $minExperience
     *
     * @return int
     */
    public function setMinLevel($minLevel)
    {
        $this->minLevel = $minLevel;

        return $this;
    }

    /**
     * Get minLevel
     *
     * @return int
     */
    public function getMinLevel()
    {
        return $this->minLevel;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \Pickl\AppBundle\Entity\User $user
     *
     * @return User
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Pickl\AppBundle\Entity\User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
