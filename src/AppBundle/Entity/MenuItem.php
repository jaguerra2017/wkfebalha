<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="menu_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuItemRepository")
 */
class MenuItem
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
     * @ORM\Column(name="name_en", type="string", length=100)
     */
    private $name_en;

    /**
     * @var string
     *
     * @ORM\Column(name="description_es", type="string", length=500, nullable=true)
     */
    private $description_es;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", cascade={"persist"})
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $menu;

    /**
     * @ORM\ManyToOne(targetEntity="Nomenclature", cascade={"persist"})
     * @ORM\JoinColumn(name="nomenclature_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $item_type;

    /**
     * @ORM\ManyToOne(targetEntity="MenuItem" , cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="url_slug_es", type="string", length=100, unique=true)
     */
    private $url_slug_es;

    /**
     * @var string
     *
     * @ORM\Column(name="url_slug_en", type="string", length=100, unique=true)
     */
    private $url_slug_en;

    /**
     * @var string
     *
     * @ORM\Column(name="tree_slug", type="string", unique=true)
     */
    private $tree_slug;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;

    /**
     * @var integer
     *
     * @ORM\Column(name="depth", type="integer")
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
        $this->description_es = null;
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
     * @param string $language
     * @return MenuItem
     */
    public function setName($name, $language = 'es')
    {
        eval('$this->name_'.$language.' = $name;');

        return $this;
    }

    /**
     * Get name
     * @param string $language
     * @return string
     */
    public function getName($language = 'es')
    {
        return eval(' return $this->name_'.$language.';');
    }

    /**
     * Set description
     *
     * @param string $description
     * @return MenuItem
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
     * Set menu
     *
     * @param \AppBundle\Entity\Menu $menu
     * @return MenuItem
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
        return $this;
    }

    /**
     * Get menu
     *
     * @return \AppBundle\Entity\Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set item_type
     *
     * @param \AppBundle\Entity\Nomenclature $item_type
     * @return MenuItem
     */
    public function setItemType($item_type)
    {
        $this->item_type = $item_type;
        return $this;
    }

    /**
     * Get item_type
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getItemType()
    {
        return $this->item_type;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\MenuItem $parent
     * @return MenuItem
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\MenuItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set url_slug
     *
     * @param string $url_slug
     * @param string $language
     * @return MenuItem
     */
    public function setUrlSlug($url_slug, $language = 'es')
    {
        eval('$this->url_slug_'.$language.' = $url_slug;');

        return $this;
    }

    /**
     * Get url_slug_es
     * @param string $language
     * @return string
     */
    public function getUrlSlug($language = 'es')
    {
        return eval(' return $this->url_slug_'.$language.';');
    }

    /**
     * Set tree_slug
     *
     * @param string $tree_slug
     * @return MenuItem
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
     * Set priority
     *
     * @param integer $priority
     * @return MenuItem
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
     * @return MenuItem
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
     * @return MenuItem
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
     * @return MenuItem
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
     * @return MenuItem
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
     * @return MenuItem
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
     * @return MenuItem
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
     * @return MenuItem
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
     * @return MenuItem
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
