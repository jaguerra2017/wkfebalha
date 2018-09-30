<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\NomType;

/**
 * @ORM\Table(name="nomenclature")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NomenclatureRepository")
 */
class Nomenclature
{
    /**
     * @var integer
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
     * @ORM\Column(name="tree_slug", type="string",  unique=true)
     */
    private $tree_slug;

    /**
     * @ORM\ManyToOne(targetEntity="NomType")
     * @ORM\JoinColumn(name="nom_type_id", referencedColumnName="id")
     */
    private $nom_type;

    /**
     * @ORM\ManyToOne(targetEntity="Nomenclature" , cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $parent;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority;

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
        $this->priority = null;
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
     * @return Nomenclature
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
     * @return Nomenclature
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
     * @return Nomenclature
     */
    public function setTreeSlug($tree_slug)
    {
        $this->tree_slug = $tree_slug;

        return $this;
    }

    /**
     * Get $tree_slug
     *
     * @return string
     */
    public function getTreeSlug()
    {
        return $this->tree_slug;
    }

    /**
     * Set nom_type
     *
     * @param \AppBundle\Entity\NomType $nom_type
     * @return Nomenclature
     */
    public function setNomType($nom_type)
    {
        $this->nom_type = $nom_type;

        return $this;
    }

    /**
     * Get nom_type
     *
     * @return \AppBundle\Entity\NomType
     */
    public function getNomType()
    {
        return $this->nom_type;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Nomenclature $parent
     * @return Nomenclature
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Nomenclature
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
     * Set depth
     *
     * @param integer $depth
     * @return Nomenclature
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
     * @return Nomenclature
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
     * @return Nomenclature
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
     * @return Nomenclature
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
     * @return Nomenclature
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

    /**
     * Set nameEs
     *
     * @param string $nameEs
     *
     * @return Nomenclature
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
     * Set urlSlugEs
     *
     * @param string $urlSlugEs
     *
     * @return Nomenclature
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
