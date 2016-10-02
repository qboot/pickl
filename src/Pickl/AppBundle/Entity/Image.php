<?php

namespace Pickl\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Pickl\AppBundle\Repository\ImageRepository")
 * @Vich\Uploadable
 */
class Image
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @Vich\UploadableField(mapping="project_image", fileNameProperty="url")
     * @Assert\Image(maxSize="1M")
     * @Assert\NotBlank()
     */
    private $file;

    /**
     * @ORM\Column(name="update_at", type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * Champ caché pour les formulaires
     */
    private $hiddenUrl = null;

    public function getHiddenUrl() {
        return $this->hiddenUrl;
    }

    public function setHiddenUrl($hiddenUrl) {
        $this->hiddenUrl = $hiddenUrl;
        return $this;
    }

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
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(File $image = null)
    {
        $this->file = $image;

        if($image) {
            $this->updateAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return Image
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    public function getUploadDir()
    {
        return 'uploads/img';
    }

    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getUrl();
    }

    public function getCatWebPath()
    {
        return 'assets/img/category/'.$this->getUrl();
    }

    public function getRewardWebPath()
    {
        return 'assets/img/reward/'.$this->getUrl();
    }
}
