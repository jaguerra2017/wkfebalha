<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nom_functionality")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NomFunctionalityRepository")
 */
class NomFunctionality
{
    /**
     *
     * @ORM\OneToOne(targetEntity="Nomenclature")
     * @ORM\JoinColumn(name="functionality_id", referencedColumnName="id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url_index_action", type="string", length=500, nullable=true)
     */
    private $url_index_action;

    /**
     * @var string
     *
     * @ORM\Column(name="keyword_selected_class", type="string", length=20, nullable=true)
     */
    private $keyword_selected_class;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_class", type="string", length=50, nullable=true)
     */
    private $icon_class;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_used_frequently", type="boolean")
     */
    private $is_used_frequently;






    /**
     * Constructor
     */
    public function __construct()
    {
        $this->url_index_action = null;
        $this->keyword_selected_class = null;
        $this->icon_class = null;
        $this->is_used_frequently = false;
    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\Nomenclature $id
     * @return NomFunctionality
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url_index_action
     *
     * @param string $url_index_action
     * @return NomFunctionality
     */
    public function setUrlIndexAction($url_index_action = null)
    {
        $this->url_index_action = $url_index_action;

        return $this;
    }

    /**
     * Get url_index_action
     *
     * @return string
     */
    public function getUrlIndexAction()
    {
        return $this->url_index_action;
    }

    /**
     * Set keyword_selected_class
     *
     * @param string $keyword_selected_class
     * @return NomFunctionality
     */
    public function setKeywordSelectedClass($keyword_selected_class = null)
    {
        $this->keyword_selected_class = $keyword_selected_class;

        return $this;
    }

    /**
     * Get keyword_selected_class
     *
     * @return string
     */
    public function getKeywordSelectedClass()
    {
        return $this->keyword_selected_class;
    }

    /**
     * Set icon_class
     *
     * @param string $icon_class
     * @return NomFunctionality
     */
    public function setIconClass($icon_class = null)
    {
        $this->icon_class = $icon_class;

        return $this;
    }

    /**
     * Get icon_class
     *
     * @return string
     */
    public function getIconClass()
    {
        return $this->icon_class;
    }

    /**
     * Set is_used_frequently
     *
     * @param boolean $is_used_frequently
     * @return NomFunctionality
     */
    public function setIsUsedFrequently($is_used_frequently)
    {
        $this->is_used_frequently = $is_used_frequently;

        return $this;
    }

    /**
     * Get is_used_frequently
     *
     * @return boolean
     */
    public function isUsedFrequently()
    {
        return $this->is_used_frequently;
    }




    /**
     * Get isUsedFrequently
     *
     * @return boolean
     */
    public function getIsUsedFrequently()
    {
        return $this->is_used_frequently;
    }
}
