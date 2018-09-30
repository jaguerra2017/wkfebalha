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
   * @ORM\Column(name="name_en", type="string", length=100, unique=false)
   */
  private $name_en;

  /**
   * @var string
   *
   * @ORM\Column(name="description_en", type="string", length=500, nullable=true)
   */
  private $description_en;

  /**
   * @var string
   *
   * @ORM\Column(name="url_slug_en", type="string", length=100, unique=false)
   */
  private $url_slug_en;

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
  public function setName($name, $language = 'es')
  {
    eval('$this->name_'.$language.' = $name;');
    return $this;
  }

  /**
   * Get name
   *
   * @return string
   */
  public function getName($language = 'es')
  {
    return eval('return $this->name_'.$language.';');
  }

  /**
   * Set description
   *
   * @param string $description
   * @return GenericPostType
   */
  public function setDescription($description, $language = 'es')
  {
    eval('$this->description_'.$language.' = $description;');

    return $this;
  }

  /**
   * Get description
   *
   * @return string
   */
  public function getDescription($language = 'es')
  {
    return eval('return $this->description_'.$language.';');
  }

  /**
   * Set url_slug
   *
   * @param string $url_slug
   * @return GenericPostType
   */
  public function setUrlSlug($url_slug, $language = 'es')
  {
    eval('$this->url_slug_'.$language.' = $url_slug;');

    return $this;
  }

  /**
   * Get url_slug
   *
   * @return string
   */
  public function getUrlSlug($language = 'es')
  {
    return eval('return $this->url_slug_'.$language.';');
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

    /**
     * Set nameEs
     *
     * @param string $nameEs
     *
     * @return GenericPostType
     */
    public function setNameEs($nameEs)
    {
        $this->name_es = $nameEs;
    
        return $this;
    }

    /**
     * Get nameEs
     *
     * @return string
     */
    public function getNameEs()
    {
        return $this->name_es;
    }

    /**
     * Set descriptionEs
     *
     * @param string $descriptionEs
     *
     * @return GenericPostType
     */
    public function setDescriptionEs($descriptionEs)
    {
        $this->description_es = $descriptionEs;
    
        return $this;
    }

    /**
     * Get descriptionEs
     *
     * @return string
     */
    public function getDescriptionEs()
    {
        return $this->description_es;
    }

    /**
     * Set urlSlugEs
     *
     * @param string $urlSlugEs
     *
     * @return GenericPostType
     */
    public function setUrlSlugEs($urlSlugEs)
    {
        $this->url_slug_es = $urlSlugEs;
    
        return $this;
    }

    /**
     * Get urlSlugEs
     *
     * @return string
     */
    public function getUrlSlugEs()
    {
        return $this->url_slug_es;
    }

    /**
     * Set nameEn
     *
     * @param string $nameEn
     *
     * @return GenericPostType
     */
    public function setNameEn($nameEn)
    {
        $this->name_en = $nameEn;
    
        return $this;
    }

    /**
     * Get nameEn
     *
     * @return string
     */
    public function getNameEn()
    {
        return $this->name_en;
    }

    /**
     * Set descriptionEn
     *
     * @param string $descriptionEn
     *
     * @return GenericPostType
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->description_en = $descriptionEn;
    
        return $this;
    }

    /**
     * Get descriptionEn
     *
     * @return string
     */
    public function getDescriptionEn()
    {
        return $this->description_en;
    }

    /**
     * Set urlSlugEn
     *
     * @param string $urlSlugEn
     *
     * @return GenericPostType
     */
    public function setUrlSlugEn($urlSlugEn)
    {
        $this->url_slug_en = $urlSlugEn;
    
        return $this;
    }

    /**
     * Get urlSlugEn
     *
     * @return string
     */
    public function getUrlSlugEn()
    {
        return $this->url_slug_en;
    }

    /**
     * Get sectionAvailable
     *
     * @return boolean
     */
    public function getSectionAvailable()
    {
        return $this->section_available;
    }

    /**
     * Get searchableType
     *
     * @return boolean
     */
    public function getSearchableType()
    {
        return $this->searchable_type;
    }
}
