<?php declare(strict_types=1);

namespace App\TimVhostingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\TimVhostingBundle\Entity\VideoSuggestRepository")
 */
class VideoSuggest
{
    public const STATUS_HOLD = 0;
    public const STATUS_REJECT = 1;
    public const STATUS_APPROVE = 2;

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
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @ORM\Column(name="user_name", type="string", length=255)
     */
    protected $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

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
     * @ORM\Column(name="created_at", type="datetime")
     **/
    private $createdAt;

    /**
     * @var ArrayCollection|Tags[]
     *
     * @ORM\ManyToMany(targetEntity="Tags", inversedBy="videoSuggests", cascade={"persist"})
     * @ORM\JoinTable(name="video_suggest_tag")
     */
    protected $tags;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="videoSuggest")
     */
    private $video;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->tags = new ArrayCollection();
        $this->status = self::STATUS_HOLD;
        $this->email = null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id ? $this->title : '';
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
     * Set title
     *
     * @param string $title
     *
     * @return VideoSuggest
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
     * Set email
     *
     * @param string $email
     *
     * @return VideoSuggest
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return VideoSuggest
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
     * Set description
     *
     * @param string $description
     *
     * @return VideoSuggest
     */
    public function setDescription($description): self
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return VideoSuggest
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return VideoSuggest
     */
    public function setUserName($userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * Add tag
     *
     * @param Tags $tag
     *
     * @return VideoSuggest
     */
    public function addTag(Tags $tag): self
    {
        // $tag->addVideoSuggest($this);
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param Tags $tag
     */
    public function removeTag(Tags $tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set status
     *
     * @param int $status
     *
     * @return VideoSuggest
     */
    public function setStatus($status): self
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVE;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECT;
    }

    public function isHold(): bool
    {
        return $this->status === self::STATUS_HOLD;
    }

    public function getStatusAsString(): string
    {
        switch ($this->status)
        {
            case self::STATUS_HOLD:
                return 'Hold';
                break;
            case self::STATUS_APPROVE:
                return 'Approved';
                break;
            case self::STATUS_REJECT:
                return 'Rejected';
                break;
        }

        return '';
    }

    /**
     * Add video
     *
     * @param Video $video
     *
     * @return VideoSuggest
     */
    public function addVideo(Video $video): self
    {
        $this->video[] = $video;
    
        return $this;
    }

    /**
     * Remove video
     *
     * @param Video $video
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeVideo(Video $video): bool
    {
        return $this->video->removeElement($video);
    }

    /**
     * Get video
     *
     * @return ArrayCollection
     */
    public function getVideo()
    {
        return $this->video;
    }
}
