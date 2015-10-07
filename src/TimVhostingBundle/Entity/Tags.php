<?php

namespace TimVhostingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TimVhostingBundle\Entity\Base\BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    public function __construct()
    {
        parent::__construct();
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
     * Set author
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     *
     * @return Tags
     */
    public function setAuthor(\Application\Sonata\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
