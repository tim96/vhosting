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

    /**
     * @ORM\ManyToOne(targetEntity="VideoSuggest", inversedBy="video")
     * @ORM\JoinColumn(name="video_suggest_id", referencedColumnName="id")
     */
    private $videoSuggest;

    /**
     * @var Tags
     *
     * @ORM\ManyToMany(targetEntity="Tags", inversedBy="videos", cascade={"persist"})
     * @ORM\JoinTable(name="video_tag")
     */
    protected $tags;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_public", type="boolean", options={"default" = false})
     */
    private $isPublic;

    /**
     * @var string
     *
     * @ORM\Column(name="youtube_video_id", type="string", nullable=true)
     */
    protected $youtubeVideoId;

    /**
     * @var string
     *
     * @ORM\Column(name="duration_video", type="string", nullable=true)
     */
    protected $durationVideo;

    /**
     * @var string
     *
     * @ORM\Column(name="description_video", type="string", nullable=true)
     */
    protected $descriptionVideo;

    /**
     * @var string
     *
     * @ORM\Column(name="view_count", type="string", nullable=true, options={"default" = 0})
     */
    protected $viewCount;

    /**
     * @var string
     *
     * @ORM\Column(name="like_count", type="string", nullable=true, options={"default" = 0})
     */
    protected $likeCount;

    /**
     * @var string
     *
     * @ORM\Column(name="dislike_count", type="string", nullable=true, options={"default" = 0})
     */
    protected $dislikeCount;

    /**
     * @var string
     *
     * @ORM\Column(name="favorite_count", type="string", nullable=true, options={"default" = 0})
     */
    protected $favoriteCount;

    public function __construct()
    {
        parent::__construct();

        $this->videoRate = new ArrayCollection();
        $this->videoSuggest = null;
        $this->youtubeVideoId = null;
        $this->isPublic = false;
        $this->durationVideo = null;
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

    /**
     * Add tag
     *
     * @param \TimVhostingBundle\Entity\Tags $tag
     *
     * @return Video
     */
    public function addTag(\TimVhostingBundle\Entity\Tags $tag)
    {
        $this->tags[] = $tag;
    
        return $this;
    }

    /**
     * Remove tag
     *
     * @param \TimVhostingBundle\Entity\Tags $tag
     */
    public function removeTag(\TimVhostingBundle\Entity\Tags $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add videoSuggest
     *
     * @param \TimVhostingBundle\Entity\VideoSuggest $videoSuggest
     *
     * @return Video
     */
    public function addVideoSuggest(\TimVhostingBundle\Entity\VideoSuggest $videoSuggest)
    {
        $this->videoSuggest[] = $videoSuggest;
    
        return $this;
    }

    /**
     * Remove videoSuggest
     *
     * @param \TimVhostingBundle\Entity\VideoSuggest $videoSuggest
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeVideoSuggest(\TimVhostingBundle\Entity\VideoSuggest $videoSuggest)
    {
        return $this->videoSuggest->removeElement($videoSuggest);
    }

    /**
     * Get videoSuggest
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideoSuggest()
    {
        return $this->videoSuggest;
    }

    /**
     * Set videoSuggest
     *
     * @param \TimVhostingBundle\Entity\VideoSuggest $videoSuggest
     *
     * @return Video
     */
    public function setVideoSuggest(\TimVhostingBundle\Entity\VideoSuggest $videoSuggest = null)
    {
        $this->videoSuggest = $videoSuggest;
    
        return $this;
    }

    /**
     * Set isPublic
     *
     * @param bool $isPublic
     *
     * @return Video
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    
        return $this;
    }

    /**
     * Get isPublic
     *
     * @return bool
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set youtubeVideoId
     *
     * @param string $youtubeVideoId
     *
     * @return Video
     */
    public function setYoutubeVideoId($youtubeVideoId)
    {
        $this->youtubeVideoId = $youtubeVideoId;
    
        return $this;
    }

    /**
     * Get youtubeVideoId
     *
     * @return string
     */
    public function getYoutubeVideoId()
    {
        return $this->youtubeVideoId;
    }

    /**
     * Set durationVideo
     *
     * @param string $durationVideo
     *
     * @return Video
     */
    public function setDurationVideo($durationVideo)
    {
        $this->durationVideo = json_encode($durationVideo);
    
        return $this;
    }

    /**
     * Get durationVideo
     *
     * @return string
     */
    public function getDurationVideo()
    {
        return json_decode($this->durationVideo, true);
    }

    public function getDurationVideoAsString()
    {
        $data = $this->getDurationVideo();
        if (is_null($data)) return '';

        return sprintf("%02d:%02d:%02d", $data['hours'],$data['minutes'],$data['seconds']);
    }

    /**
     * Set descriptionVideo
     *
     * @param string $descriptionVideo
     *
     * @return Video
     */
    public function setDescriptionVideo($descriptionVideo)
    {
        $this->descriptionVideo = $descriptionVideo;
    
        return $this;
    }

    /**
     * Get descriptionVideo
     *
     * @return string
     */
    public function getDescriptionVideo()
    {
        return $this->descriptionVideo;
    }

    /**
     * Set viewCount
     *
     * @param string $viewCount
     *
     * @return Video
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;
    
        return $this;
    }

    /**
     * Get viewCount
     *
     * @return string
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * Set likeCount
     *
     * @param string $likeCount
     *
     * @return Video
     */
    public function setLikeCount($likeCount)
    {
        $this->likeCount = $likeCount;
    
        return $this;
    }

    /**
     * Get likeCount
     *
     * @return string
     */
    public function getLikeCount()
    {
        return $this->likeCount;
    }

    /**
     * Set dislikeCount
     *
     * @param string $dislikeCount
     *
     * @return Video
     */
    public function setDislikeCount($dislikeCount)
    {
        $this->dislikeCount = $dislikeCount;
    
        return $this;
    }

    /**
     * Get dislikeCount
     *
     * @return string
     */
    public function getDislikeCount()
    {
        return $this->dislikeCount;
    }

    /**
     * Set favoriteCount
     *
     * @param string $favoriteCount
     *
     * @return Video
     */
    public function setFavoriteCount($favoriteCount)
    {
        $this->favoriteCount = $favoriteCount;
    
        return $this;
    }

    /**
     * Get favoriteCount
     *
     * @return string
     */
    public function getFavoriteCount()
    {
        return $this->favoriteCount;
    }
}
