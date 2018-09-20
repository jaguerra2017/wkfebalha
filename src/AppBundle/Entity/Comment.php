<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\Column(name="author_name", type="string", length=200)
     */
    private $author_name;

    /**
     * @ORM\Column(name="email", type="string", length=60, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=5000)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Nomenclature")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="GenericPost", cascade={"persist"})
     * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $generic_post;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_auto_comment", type="boolean")
     */
    private $is_auto_comment;

    /**
     * @var integer
     *
     * @ORM\Column(name="depth", type="integer", length=1)
     */
    private $depth;



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
     * @ORM\Column(name="published_date", type="datetime", nullable=true)
     */
    private $published_date;





    /**
     * Constructor
     */
    public function __construct()
    {
        $this->depth = 1;
        $this->email = null;
        $this->parent = null;
        $this->is_auto_comment = false;
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
     * Set author_name
     *
     * @param string $author_name
     * @return Comment
     */
    public function setAuthorName($author_name)
    {
        $this->author_name = $author_name;
        return $this;
    }

    /**
     * Get author_name
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->author_name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Comment
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Comment $parent
     * @return Comment
     */
    public function setParent(\AppBundle\Entity\Comment $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Comment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set status
     *
     * @param \AppBundle\Entity\Nomenclature $status
     * @return Comment
     */
    public function setStatus(\AppBundle\Entity\Nomenclature $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set generic_post
     *
     * @param \AppBundle\Entity\GenericPost $generic_post
     * @return Comment
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
     * Set is_auto_comment
     *
     * @param boolean $is_auto_comment
     * @return Comment
     */
    public function setIsAutoComment($is_auto_comment)
    {
        $this->is_auto_comment = $is_auto_comment;
        return $this;
    }

    /**
     * Get is_auto_comment
     *
     * @return boolean
     */
    public function isAutoComment()
    {
        return $this->is_auto_comment;
    }

    /**
     * Set depth
     *
     * @param integer $depth
     * @return Comment
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
        return $this;
    }

    /**
     * Get depth
     *
     * @return integer
     */
    public function getDepth()
    {
        return $this->is_auto_comment;
    }


    /**
     * Set created_date
     *
     * @param \DateTime $created_date
     * @return Comment
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
     * @return Comment
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
     * @param \DateTime $modifiedDate
     * @return Comment
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
     * @return Comment
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

    /**
     * Set published_date
     *
     * @param \DateTime $published_date
     * @return Comment
     */
    public function setPublishedDate($published_date)
    {
        $this->published_date = $published_date;
        return $this;
    }

    /**
     * Get published_date
     *
     * @return \DateTime
     */
    public function getPublishedDate()
    {
        return $this->published_date;
    }



    function __toString()
    {
        return $this->author_name;
    }
}