<?php declare(strict_types = 1);

namespace TimVhostingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use TimVhostingBundle\Entity\Base\BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TimVhostingBundle\Entity\LanguageRepository")
 */
class Language extends BaseEntity
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
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @ORM\Column(name="code", type="string", length=2, unique=true)
     */
    protected $code;

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="language")
     */
    private $video;

    public function __construct()
    {
        parent::__construct();

        $this->video = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->id ? (string)$this->name : "";
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->video->contains($video)) {
            $this->video->add($video);
        }

        return $this;
    }

    public function removeVideo(Video $video): bool
    {
        return $this->video->removeElement($video);
    }

    public function getVideo(): ArrayCollection
    {
        return $this->video;
    }
}
