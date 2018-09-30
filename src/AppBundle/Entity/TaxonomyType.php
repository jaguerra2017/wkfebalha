<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="taxonomy_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaxonomyTypeRepository")
 */
class TaxonomyType
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
     * Constructor
     */
    public function __construct()
    {
        $this->description_es = null;
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
     * @return TaxonomyType
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
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return TaxonomyType
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
     * @return TaxonomyType
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
     * @return TaxonomyType
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



    function __toString()
    {
        return $this->name_es;
    }

    /**
     * Set nameEs
     *
     * @param string $nameEs
     *
     * @return TaxonomyType
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
     * @return TaxonomyType
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
     * @return TaxonomyType
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
}
