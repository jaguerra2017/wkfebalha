<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="media_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaImageRepository")
 */
class MediaImage
{
    /**
     *
     * @ORM\OneToOne(targetEntity="Media", cascade={"persist"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="alternative_text_es", type="string", length=250, nullable=true)
     */
    private $alternative_text_es;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=5)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="dimension", type="string")
     */
    private $dimension;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_loaded_by_system", type="boolean")
     */
    private $is_loaded_by_system;
    




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->alternative_text_es = null;
        $this->is_loaded_by_system = false;
    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\Media $id
     * @return MediaImage
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return \AppBundle\Entity\Media
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set alternative_text
     *
     * @param string $alternative_text
     * @return MediaImage
     */
    public function setAlternativeText($alternative_text)
    {
        $this->alternative_text_es = $alternative_text;

        return $this;
    }

    /**
     * Get alternative_text
     *
     * @return string
     */
    public function getAlternativeText()
    {
        return $this->alternative_text_es;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return MediaImage
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return MediaImage
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set dimension
     *
     * @param string $dimension
     * @return MediaImage
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * Get dimension
     *
     * @return string
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Set is_loaded_by_system
     *
     * @param boolean $is_loaded_by_system
     * @return MediaImage
     */
    public function setIsLoadedBySystem($is_loaded_by_system)
    {
        $this->is_loaded_by_system = $is_loaded_by_system;

        return $this;
    }

    /**
     * Get is_loaded_by_system
     *
     * @return boolean
     */
    public function getIsLoadedBySystem()
    {
        return $this->is_loaded_by_system;
    }



}