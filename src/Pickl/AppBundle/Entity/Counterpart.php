<?php

namespace Pickl\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Counterpart
 *
 * @ORM\Table(name="counterpart")
 * @ORM\Entity(repositoryClass="Pickl\AppBundle\Repository\CounterpartRepository")
 */
class Counterpart
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Length(min=10, minMessage="This value is too short. It should have {{ limit }}")
     * @Assert\Length(max=30, maxMessage="This value is too long. It should have {{ limit }}")
     * @Assert\Type(type="string", message="This value is should be a valid string")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\Length(min=10, minMessage="This value is too short. It should have {{ limit }}")
     * @Assert\Length(max=255, maxMessage="This value is too long. It should have {{ limit }}")
     * @Assert\Type(type="string", message="This value is should be a valid string")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="min_amount", type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="integer", message="The value {{ value }} should be a valid number")
     * @Assert\Range(
     *     min = 1,
     *     minMessage = "You must request at least ${{ limit }}"
     * )
     */
    private $minAmount;

    /**
     * @ORM\ManyToOne(targetEntity="Pickl\AppBundle\Entity\Project", inversedBy="counterparts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @var int
     *
     * @ORM\Column(name="max_counterparts", type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="integer", message="The value {{ value }} should be a valid number")
     * @Assert\Range(
     *     min = 10,
     *     minMessage = "You must have at least {{ limit }} in stock"
     * )
     */
    private $maxCounterparts;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_counterparts", type="integer")
     */
    private $nbCounterparts = 0;

    /**
     * @ORM\OneToMany(targetEntity="Pickl\AppBundle\Entity\Contribution", cascade={"remove"}, mappedBy="counterpart")
     */
    private $contributions;

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
     * Set title
     *
     * @param string $title
     *
     * @return string
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Set minAmount
     *
     * @param integer $minAmount
     *
     * @return int
     */
    public function setMinAmount($minAmount)
    {
        $this->minAmount = $minAmount;

        return $this;
    }

    /**
     * Get minAmount
     *
     * @return int
     */
    public function getMinAmount()
    {
        return $this->minAmount;
    }

    /**
     * Set project
     *
     * @param \Pickl\AppBundle\Entity\Project $project
     *
     * @return Project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }


    /********
     * New getters
     *******/


    /**
     * @return mixed
     */
    public function getMaxCounterparts()
    {
        return $this->maxCounterparts;
    }


    /**
     * @param mixed $maxCounterparts
     */
    public function setMaxCounterparts($maxCounterparts)
    {
        $this->maxCounterparts = $maxCounterparts;
    }

    /**
     * @return mixed
     */
    public function getNbCounterparts()
    {
        return $this->nbCounterparts;
    }

    /**
     * @param mixed $nbCounterparts
     */
    public function setNbCounterparts($nbCounterparts)
    {
        $this->nbCounterparts = $nbCounterparts;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contributions = new ArrayCollection();
    }

    /**
     * Add contribution
     *
     * @param \Pickl\AppBundle\Entity\Contribution $contribution
     *
     * @return Counterpart
     */
    public function addContribution(Contribution $contribution)
    {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution
     *
     * @param \Pickl\AppBundle\Entity\Contribution $contribution
     */
    public function removeContribution(Contribution $contribution)
    {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContributions()
    {
        return $this->contributions;
    }
}
