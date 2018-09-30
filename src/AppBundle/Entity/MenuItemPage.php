<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="menu_item_page")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuItemPageRepository")
 */
class MenuItemPage
{
    /**
     *
     * @ORM\OneToOne(targetEntity="MenuItem", cascade={"persist"})
     * @ORM\JoinColumn(name="menu_item_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="GenericPost")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=50, nullable=true)
     */
    private $tag;




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tag = null;
    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\MenuItem $id
     * @return MenuItemPage
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return \AppBundle\Entity\MenuItem
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set page
     *
     * @param \AppBundle\Entity\GenericPost $page
     * @return MenuItemPage
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Get page
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set tag
     *
     * @param string $tag
     * @return MenuItemPage
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }


}
