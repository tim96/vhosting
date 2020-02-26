<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 10/6/2015
 * Time: 9:16 PM
 */

namespace App\TimVhostingBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * BaseEntity
 *
 * @ORM\MappedSuperclass
 **/
abstract class BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     **/
    private $author;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     **/
    private $updatedAt;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     **/
    private $createdAt;

    /**
     * @ORM\Column(name="is_deleted", type="boolean", options={"default": false})
     **/
    private $isDeleted = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isDeleted = false;
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        // $this->createdBy = null;
        // $this->updatedBy = null;
        $this->author = null;
    }

    /**
     * To string
     *
     * @return string
     */
    public abstract function __toString();

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return BaseEntity
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return BaseEntity
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
     * Set isDeleted
     *
     * @param bool $isDeleted
     *
     * @return BaseEntity
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    
        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return bool
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set author
     *
     * @param \App\Application\Sonata\UserBundle\Entity\User $author
     *
     * @return BaseEntity
     */
    public function setAuthor(\App\Application\Sonata\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return \App\Application\Sonata\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
