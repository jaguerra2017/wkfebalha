<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="menu_item_link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuItemLinkRepository")
 */
class MenuItemLink
{
    /**
     *
     * @ORM\OneToOne(targetEntity="MenuItem" , cascade={"persist"})
     * @ORM\JoinColumn(name="menu_item_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="link_url", type="string", length=500)
     */
    private $link_url;




    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\MenuItem $id
     * @return MenuItemLink
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
     * Set link_url
     *
     * @param string $link_url
     * @return MenuItemLink
     */
    public function setLinkUrl($link_url)
    {
        $this->link_url = $link_url;
        return $this;
    }

    /**
     * Get link_url
     *
     * @return string
     */
    public function getLinkUrl()
    {
        return $this->link_url;
    }


}
