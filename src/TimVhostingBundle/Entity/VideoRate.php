<?php

namespace TimVhostingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TimVhostingBundle\Entity\Base\BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * VideoRate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TimVhostingBundle\Entity\VideoRateRepository")
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
    public function getId()
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
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;
    
        return $this;
    }

    /**
     * Get feedback
     *
     * @return string
     */
    public function getFeedback()
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
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set rating
     *
     * @param $rating
     *
     * @return VideoRate
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set video
     *
     * @param \TimVhostingBundle\Entity\Video $video
     *
     * @return VideoRate
     */
    public function setVideo(\TimVhostingBundle\Entity\Video $video = null)
    {
        $this->video = $video;
    
        return $this;
    }

    /**
     * Get video
     *
     * @return \TimVhostingBundle\Entity\Video
     */
    public function getVideo()
    {
        return $this->video;
    }
}
