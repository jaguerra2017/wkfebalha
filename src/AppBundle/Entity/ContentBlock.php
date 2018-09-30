<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="content_block")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentBlockRepository")
 */
class ContentBlock
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
     * @ORM\Column(name="title_es", type="string", length=200)
     */
    private $title_es;

    /**
     * @ORM\ManyToOne(targetEntity="Nomenclature")
     * @ORM\JoinColumn(name="content_block_type_id", referencedColumnName="id")
     */
    private $content_block_type;

    /**
     * @ORM\ManyToOne(targetEntity="GenericPost" , cascade={"persist"})
     * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $generic_post;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority;


    /**
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $created_date;

    /**
     * @ORM\ManyToOne(targetEntity="User" , cascade={"persist"})
     * @ORM\JoinColumn(name="created_by_user_id", referencedColumnName="id", onDelete="RESTRICT", nullable=true)
     */
    private $created_author;

    /**
     * @ORM\Column(name="modified_date", type="datetime", nullable=true)
     */
    private $modified_date;

    /**
     * @ORM\ManyToOne(targetEntity="User" , cascade={"persist"})
     * @ORM\JoinColumn(name="modified_by_user_id", referencedColumnName="id", onDelete="RESTRICT", nullable=true)
     */
    private $modified_author;




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->priority = null;
        $this->created_date = new \DateTime();
        $this->created_author = null;
        $this->modified_date = null;
        $this->modified_author = null;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ContentBlock
     */
    public function setTitle($title)
    {
        $this->title_es = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title_es;
    }

    /**
     * Set content_block_type
     *
     * @param \AppBundle\Entity\Nomenclature $content_block_type
     * @return ContentBlock
     */
    public function setContentBlockType(\AppBundle\Entity\Nomenclature $content_block_type)
    {
        $this->content_block_type = $content_block_type;
        return $this;
    }

    /**
     * Get content_block_type
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getContentBlockType()
    {
        return $this->content_block_type;
    }

    /**
     * Set generic_post
     *
     * @param \AppBundle\Entity\GenericPost $generic_post
     * @return ContentBlock
     */
    public function setGenericPost(\AppBundle\Entity\GenericPost $generic_post)
    {
        $this->generic_post = $generic_post;
        return $this;
    }

    /**
     * Get generic_post
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getGenericPost()
    {
        return $this->generic_post;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return ContentBlock
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Get priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set created_date
     *
     * @param \DateTime $created_date
     * @return ContentBlock
     */
    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;
        return $this;
    }

    /**
     * Get created_date
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Set created_author
     *
     * @param \AppBundle\Entity\User $created_author
     * @return ContentBlock
     */
    public function setCreatedAuthor($created_author)
    {
        $this->created_author = $created_author;
        return $this;
    }

    /**
     * Get createdAuthor
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedAuthor()
    {
        return $this->created_author;
    }

    /**
     * Set modified_date
     *
     * @param \DateTime $modified_date
     * @return ContentBlock
     */
    public function setModifiedDate($modified_date)
    {
        $this->modified_date = $modified_date;
        return $this;
    }

    /**
     * Get modified_date
     *
     * @return \DateTime
     */
    public function getModifiedDate()
    {
        return $this->modified_date;
    }

    /**
     * Set modified_author
     *
     * @param \AppBundle\Entity\User $modified_author
     * @return ContentBlock
     */
    public function setModifiedAuthor($modified_author)
    {
        $this->modified_author = $modified_author;
        return $this;
    }

    /**
     * Get modified_author
     *
     * @return \AppBundle\Entity\User
     */
    public function getModifiedAuthor()
    {
        return $this->modified_author;
    }



    function __toString()
    {
        return $this->title_es;
    }

    /**
     * Set titleEs
     *
     * @param string $titleEs
     *
     * @return ContentBlock
     */
    public function setTitleEs($titleEs)
    {
        $this->title_es = $titleEs;
    
        return $this;
    }

    /**
     * Get titleEs
     *
     * @return string
     */
    public function getTitleEs()
    {
        return $this->title_es;
    }
}
