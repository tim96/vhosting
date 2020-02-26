<?php declare(strict_types=1);

namespace App\TimVhostingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\TimVhostingBundle\Entity\Base\BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
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
     * @var ArrayCollection|Video[]
     *
     * @ORM\ManyToMany(targetEntity="Video", mappedBy="tags", cascade={"persist"})
     */
    protected $videos;

    /**
     * @var ArrayCollection|VideoSuggest[]
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

    /**
     * @var integer
     *
     * Depend on how much videos exists for this tag (<10 -> 1, <25 -> 2, <50 -> 3, <100 -> 4,  other -> 5
     * @ORM\Column(name="classification_number", type="integer")
     */
    protected $classificationNumber;

    public function __construct()
    {
        parent::__construct();

        $this->videos = new ArrayCollection();
        $this->videoSuggests = new ArrayCollection();
        $this->countVideo = 0;
        $this->classificationNumber = 0;
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
        return $this->id ? (string)$this->name : '';
    }

    public function setName($name): self
    {
        $this->name = $name;
    
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function addVideo(Video $video): self
    {
        $this->videos[] = $video;
    
        return $this;
    }

    public function removeVideo(Video $video): bool
    {
        return $this->videos->removeElement($video);
    }

    /**
     * Get videos
     *
     * @return ArrayCollection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Add videoSuggest
     *
     * @param VideoSuggest $videoSuggest
     *
     * @return Tags
     */
    public function addVideoSuggest(VideoSuggest $videoSuggest): self
    {
        $this->videoSuggests[] = $videoSuggest;
    
        return $this;
    }

    public function removeVideoSuggest(VideoSuggest $videoSuggest): bool
    {
        return $this->videoSuggests->removeElement($videoSuggest);
    }

    /**
     * Get videoSuggests
     *
     * @return ArrayCollection
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
    public function setCountVideo($countVideo): self
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

    /**
     * Set classificationNumber
     *
     * @param int $classificationNumber
     *
     * @return Tags
     */
    public function setClassificationNumber($classificationNumber): self
    {
        $this->classificationNumber = $classificationNumber;
    
        return $this;
    }

    /**
     * Get classificationNumber
     *
     * @return int
     */
    public function getClassificationNumber()
    {
        return $this->classificationNumber;
    }

    public function calculateClassification($countVideo): int
    {
        // todo: Add cosntanst here and move to separate class
        // Depend on how much videos exists for this tag (<10 -> 1, <25 -> 2, <50 -> 3, <100 -> 4,  other -> 5
        if ($countVideo >= 100) {
            return 5;
        } elseif ($countVideo >= 50) {
            return 4;
        } elseif ($countVideo >= 25) {
            return 3;
        } elseif ($countVideo >= 10) {
            return 2;
        } else {
            return 1;
        }
    }
}
