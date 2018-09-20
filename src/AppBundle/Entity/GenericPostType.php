<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="generic_post_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenericPostTypeRepository")
 */
class GenericPostType
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
     * @ORM\Column(name="name_es", type="string", length=100, unique=true)
     */
    private $name_es;

    /**
     * @var string
     *
     * @ORM\Column(name="description_es", type="string", length=500, nullable=true)
     */
    private $description_es;

    /**
     * @var string
     *
     * @ORM\Column(name="url_slug_es", type="string", length=100, unique=true)
     */
    private $url_slug_es;

    /**
     * @var string
     *
     * @ORM\Column(name="tree_slug", type="string", length=100, unique=true)
     */
    private $tree_slug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="section_available", type="boolean")
     */
    private $section_available;

    /**
     * @var boolean
     *
     * @ORM\Column(name="searchable_type", type="boolean")
     */
    private $searchable_type;






    /**
     * Constructor
     */
    public function __construct()
    {
        $this->description_es = null;
        $this->section_available = false;
        $this->searchable_type = false;
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
     * @return GenericPostType
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
     * Set description
     *
     * @param string $description
     * @return GenericPostType
     */
    public function setDescription($description)
    {
        $this->description_es = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description_es;
    }

    /**
     * Set url_slug
     *
     * @param string $url_slug
     * @return GenericPostType
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
     * Set tree_slug
     *
     * @param string $tree_slug
     * @return GenericPostType
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
     * Set section_available
     *
     * @param boolean $section_available
     * @return GenericPostType
     */
    public function setSectionAvailable($section_available)
    {
        $this->section_available = $section_available;
        return $this;
    }

    /**
     * Get section_available
     *
     * @return boolean
     */
    public function isSectionAvailable()
    {
        return $this->section_available;
    }

    /**
     * Set searchable_type
     *
     * @param boolean $searchable_type
     * @return GenericPostType
     */
    public function setSearchableType($searchable_type)
    {
        $this->searchable_type = $searchable_type;
        return $this;
    }

    /**
     * Get searchable_type
     *
     * @return boolean
     */
    public function isSearchableType()
    {
        return $this->searchable_type;
    }



    function __toString()
    {
        return $this->name_es;
    }
}