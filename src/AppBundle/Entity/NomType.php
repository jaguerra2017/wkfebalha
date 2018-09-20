<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nomenclature_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NomTypeRepository")
 */
class NomType
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
        $this->description = null;
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
     * @return NomType
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
     * @return NomType
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
     * @return NomType
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
     * @return NomType
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
}