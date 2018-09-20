<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="taxonomy")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaxonomyRepository")
 */
class Taxonomy
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
     * @ORM\Column(name="name_es", type="string", length=100)
     */
    private $name_es;

    /**
     * @var string
     *
     * @ORM\Column(name="url_slug_es", type="string", length=100, unique=true)
     */
    private $url_slug_es;

    /**
     * @var string
     *
     * @ORM\Column(name="tree_slug", type="string", unique=true)
     */
    private $tree_slug;

    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyType")
     * @ORM\JoinColumn(name="taxonomy_type_id", referencedColumnName="id")
     */
    private $taxonomy_type;

    /**
     * @ORM\ManyToOne(targetEntity="Taxonomy" , cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $parent;

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
     * Constructor
     */
    public function __construct()
    {
        $this->parent = null;
        $this->depth = 1;
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
     * Set name
     *
     * @param string $name
     * @return Taxonomy
     */
    public function setName($name)
    {
        $this->name_es = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name_es;
    }

    /**
     * Set url_slug
     *
     * @param string $url_slug
     * @return Taxonomy
     */
    public function setUrlSlug($url_slug)
    {
        $this->url_slug_es = $url_slug;

        return $this;
    }

    /**
     * Get url_slug_es
     *
     * @return string
     */
    public function getUrlSlug()
    {
        return $this->url_slug_es;
    }

    /**
     * Set tree_slug
     *
     * @param string $tree_slug
     * @return Taxonomy
     */
    public function setTreeSlug($tree_slug)
    {
        $this->tree_slug = $tree_slug;

        return $this;
    }

    /**
     * Get tree_slug
     *
     * @return string
     */
    public function getTreeSlug()
    {
        return $this->tree_slug;
    }


    /**
     * Set taxonomy_type
     *
     * @param \AppBundle\Entity\TaxonomyType $taxonomy_type
     * @return Taxonomy
     */
    public function setTaxonomyType(\AppBundle\Entity\TaxonomyType $taxonomy_type)
    {
        $this->taxonomy_type = $taxonomy_type;

        return $this;
    }

    /**
     * Get taxonomy_type
     *
     * @return \AppBundle\Entity\TaxonomyType
     */
    public function getTaxonomyType()
    {
        return $this->taxonomy_type;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Taxonomy $parent
     * @return Taxonomy
     */
    public function setParent(\AppBundle\Entity\Taxonomy $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Taxonomy
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set depth
     *
     * @param integer $depth
     * @return Taxonomy
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
        return $this->depth;
    }

    /**
     * Set created_date
     *
     * @param \DateTime $created_date
     * @return Taxonomy
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
     * @return Taxonomy
     */
    public function setCreatedAuthor($created_author)
    {
        $this->created_author = $created_author;
        return $this;
    }

    /**
     * Get created_author
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
     * @return Taxonomy
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
     * @return Taxonomy
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
        return $this->name_es;
    }
}