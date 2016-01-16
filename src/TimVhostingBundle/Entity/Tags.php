<?php

namespace TimVhostingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TimVhostingBundle\Entity\Base\BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tags
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TimVhostingBundle\Entity\TagsRepository")
 */
class Tags extends BaseEntity
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var Video
     *
     * @ORM\ManyToMany(targetEntity="Video", mappedBy="tags", cascade={"persist"})
     */
    protected $videos;

    /**
     * @var VideoSuggest
     *
     * @ORM\ManyToMany(targetEntity="VideoSuggest", mappedBy="tags", cascade={"persist"})
     */
    protected $videoSuggests;

    /**
     * @var integer
     *
     * @ORM\Column(name="count_video", type="integer")
     */
    protected $countVideo;

    public function __construct()
    {
        parent::__construct();

        $this->videos = new ArrayCollection();
        $this->videoSuggests = new ArrayCollection();
        $this->countVideo = 0;
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
        return $this->id ? (string)$this->name : "";
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tags
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
     * Add video
     *
     * @param \TimVhostingBundle\Entity\Video $video
     *
     * @return Tags
     */
    public function addVideo(\TimVhostingBundle\Entity\Video $video)
    {
        $this->videos[] = $video;
    
        return $this;
    }

    /**
     * Remove video
     *
     * @param \TimVhostingBundle\Entity\Video $video
     */
    public function removeVideo(\TimVhostingBundle\Entity\Video $video)
    {
        $this->videos->removeElement($video);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Add videoSuggest
     *
     * @param \TimVhostingBundle\Entity\VideoSuggest $videoSuggest
     *
     * @return Tags
     */
    public function addVideoSuggest(\TimVhostingBundle\Entity\VideoSuggest $videoSuggest)
    {
        $this->videoSuggests[] = $videoSuggest;
    
        return $this;
    }

    /**
     * Remove videoSuggest
     *
     * @param \TimVhostingBundle\Entity\VideoSuggest $videoSuggest
     */
    public function removeVideoSuggest(\TimVhostingBundle\Entity\VideoSuggest $videoSuggest)
    {
        $this->videoSuggests->removeElement($videoSuggest);
    }

    /**
     * Get videoSuggests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideoSuggests()
    {
        return $this->videoSuggests;
    }

    /**
     * Set countVideo
     *
     * @param int $countVideo
     *
     * @return Tags
     */
    public function setCountVideo($countVideo)
    {
        $this->countVideo = $countVideo;
    
        return $this;
    }

    /**
     * Get countVideo
     *
     * @return int
     */
    public function getCountVideo()
    {
        return $this->countVideo;
    }
}
