<?php declare(strict_types=1);

namespace App\TimVhostingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * VideoRate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\TimVhostingBundle\Entity\VideoRateRepository")
 */
class VideoRate
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
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="Video", inversedBy="videoRate", cascade={"persist", "remove"})
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="feedback", type="text", nullable=true)
     */
    protected $feedback;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     **/
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    public function __construct()
    {
        $this->feedback = null;
        $this->createdAt = new \DateTime();
        $this->rating = 0;
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id ? (string)$this->rating : '';
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set feedback
     *
     * @param string $feedback
     *
     * @return VideoRate
     */
    public function setFeedback($feedback): self
    {
        $this->feedback = $feedback;
    
        return $this;
    }

    /**
     * Get feedback
     *
     * @return string
     */
    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return VideoRate
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

    public function setRating(int $rating): self
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * Set video
     *
     * @param Video $video
     *
     * @return VideoRate
     */
    public function setVideo(Video $video = null): self
    {
        $this->video = $video;
    
        return $this;
    }

    public function getVideo(): ?Video
    {
        return $this->video;
    }
}
