<?php

namespace Pickl\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserReward
 *
 * @ORM\Table(name="user_reward")
 * @ORM\Entity(repositoryClass="Pickl\AppBundle\Repository\UserRewardRepository")
 */
class UserReward
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Pickl\AppBundle\Entity\User", inversedBy="rewards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Pickl\AppBundle\Entity\Reward", cascade={"persist"}, inversedBy="userRewards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reward;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return \DateTime
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function __construct()
    {
        $this->date = new \Datetime();
    }

    /**
     * Set user
     *
     * @param \Pickl\AppBundle\Entity\User $user
     *
     * @return User
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set reward
     *
     * @param \Pickl\AppBundle\Entity\Reward $reward
     *
     * @return Reward
     */
    public function setReward(Reward $reward)
    {
        $this->reward = $reward;

        return $this;
    }

    /**
     * Get reward
     *
     * @return Reward
     */
    public function getReward()
    {
        return $this->reward;
    }
}
