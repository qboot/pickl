<?php

namespace Pickl\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reward
 *
 * @ORM\Table(name="reward")
 * @ORM\Entity(repositoryClass="Pickl\AppBundle\Repository\RewardRepository")
 */
class Reward
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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="Pickl\AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity="Pickl\AppBundle\Entity\UserReward", mappedBy="reward")
     */
    private $userRewards;

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
     * Set description
     *
     * @param string $description
     *
     * @return string
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set icon
     *
     * @param \Pickl\AppBundle\Entity\Image $icon
     *
     * @return Image
     */
    public function setIcon(Image $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return Image
     */
    public function getIcon()
    {
        return $this->icon;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userRewards = new ArrayCollection();
    }

    /**
     * Add reward
     *
     * @param \Pickl\AppBundle\Entity\UserReward $reward
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function addUserReward(UserReward $userReward)
    {
        $this->userRewards[] = $userReward;

        return $this;
    }

    /**
     * Remove reward
     *
     * @param \Pickl\AppBundle\Entity\UserReward $reward
     */
    public function removeUserReward(UserReward $userReward)
    {
        $this->userRewards->removeElement($userReward);
    }

    /**
     * Get rewards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserRewards()
    {
        return $this->userRewards;
    }

}
