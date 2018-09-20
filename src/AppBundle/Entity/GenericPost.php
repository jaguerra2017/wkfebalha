<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="generic_post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenericPostRepository")
 */
class GenericPost
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
     * @ORM\Column(name="title_es", type="string", length=200, nullable=true)
     */
    private $title_es;

    /**
     * @var string
     *
     * @ORM\Column(name="url_slug_es", type="string", length=200, nullable=true)
     */
    private $url_slug_es;

    /**
     * @var string
     *
     * @ORM\Column(name="excerpt_es", type="string", length=1000, nullable=true)
     */
    private $excerpt_es;

    /**
     * @var string
     *
     * @ORM\Column(name="content_es", type="string",length=1000000000, nullable=true)
     */
    private $content_es;

    /**
     * @var boolean
     *
     * @ORM\Column(name="have_featured_image", type="boolean")
     */
    private $have_featured_image;

    /**
     * @ORM\ManyToOne(targetEntity="Media" , cascade={"persist"})
     * @ORM\JoinColumn(name="featured_image_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $featured_image;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="GenericPostType")
     * @ORM\JoinColumn(name="generic_post_type_id", referencedColumnName="id")
     */
    private $generic_post_type;

    /**
     * @var string
     *
     * @ORM\Column(name="post_status_slug", type="string", length=50, nullable=true)
     */
    private $post_status_slug;


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
        $this->title_es = null;
        $this->url_slug_es = null;
        $this->excerpt_es = null;
        $this->content_es = null;
        $this->have_featured_image = false;
        $this->featured_image = null;
        $this->priority = null;
        $this->post_status_slug = null;
        $this->created_date = new \DateTime();
        $this->created_author = null;
        $this->modified_date = null;
        $this->modified_author = null;
        $this->published_date = null;
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
     * @return GenericPost
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
     * Set content
     *
     * @param string $content
     * @return GenericPost
     */
    public function setContent($content)
    {
        $this->content_es = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content_es;
    }

    /**
     * Set url_slug
     *
     * @param string $url_slug
     * @return GenericPost
     */
    public function setUrlSlug($url_slug)
    {
        $this->url_slug_es = $url_slug;

        return $this;
    }

    /**
     * Get url_slug
     *
     * @return string
     */
    public function getUrlSlug()
    {
        return $this->url_slug_es;
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     * @return GenericPost
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt_es = $excerpt;

        return $this;
    }

    /**
     * Get excerpt_es
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt_es;
    }

    /**
     * Set have_featured_image
     *
     * @param boolean $have_featured_image
     * @return GenericPost
     */
    public function setHaveFeaturedImage($have_featured_image)
    {
        $this->have_featured_image = $have_featured_image;
        return $this;
    }

    /**
     * Get have_featured_image
     *
     * @return boolean
     */
    public function getHaveFeaturedImage()
    {
        return $this->have_featured_image;
    }

    /**
     * Set featured_image
     *
     * @param \AppBundle\Entity\Media $featured_image
     * @return GenericPost
     */
    public function setFeaturedImage($featured_image)
    {
        $this->featured_image = $featured_image;
        return $this;
    }

    /**
     * Get featured_image
     *
     * @return \AppBundle\Entity\Media
     */
    public function getFeaturedImage()
    {
        return $this->featured_image;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return GenericPost
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
     * Set generic_post_type
     *
     * @param \AppBundle\Entity\GenericPostType $generic_post_type
     * @return GenericPost
     */
    public function setGenericPostType($generic_post_type)
    {
        $this->generic_post_type = $generic_post_type;
        return $this;
    }

    /**
     * Get generic_post_type
     *
     * @return \AppBundle\Entity\GenericPostType
     */
    public function getGenericPostType()
    {
        return $this->generic_post_type;
    }

    /**
     * Set post_status_slug
     *
     * @param string $post_status_slug
     * @return GenericPost
     */
    public function setPostStatusSlug($post_status_slug)
    {
        $this->post_status_slug = $post_status_slug;
        return $this;
    }

    /**
     * Get post_status_slug
     *
     * @return string
     */
    public function getPostStatusSlug()
    {
        return $this->post_status_slug;
    }

    /**
     * Set created_date
     *
     * @param \DateTime $created_date
     * @return GenericPost
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
     * @return GenericPost
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
     * @return GenericPost
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
     * @return GenericPost
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
     * @return GenericPost
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
        return $this->title_es;
    }
}