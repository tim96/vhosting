<?php

namespace TimVhostingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use TimVhostingBundle\Entity\Base\BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Video
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TimVhostingBundle\Entity\VideoRepository")
 */
class Video extends BaseEntity
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
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @ORM\Column(name="link", type="string", length=255)
     */
    protected $link;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="meta", type="text", nullable=true)
     */
    protected $meta;

    /**
     * @ORM\OneToMany(targetEntity="VideoRate", mappedBy="video")
     */
    private $videoRate;

    public function __construct()
    {
        parent::__construct();

        $this->videoRate = new ArrayCollection();
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
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id ? (string)$this->name : null;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Video
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
     * @return Video
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
     * Add videoRate
     *
     * @param \TimVhostingBundle\Entity\VideoRate $videoRate
     *
     * @return Video
     */
    public function addVideoRate(\TimVhostingBundle\Entity\VideoRate $videoRate)
    {
        $this->videoRate[] = $videoRate;
    
        return $this;
    }

    /**
     * Remove videoRate
     *
     * @param \TimVhostingBundle\Entity\VideoRate $videoRate
     */
    public function removeVideoRate(\TimVhostingBundle\Entity\VideoRate $videoRate)
    {
        $this->videoRate->removeElement($videoRate);
    }

    /**
     * Get videoRate
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideoRate()
    {
        return $this->videoRate;
    }

    /**
     * Set meta
     *
     * @param string $meta
     *
     * @return Video
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    
        return $this;
    }

    /**
     * Get meta
     *
     * @return string
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Video
     */
    public function setLink($link)
    {
        $this->link = $link;
    
        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
