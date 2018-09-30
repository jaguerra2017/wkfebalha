<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="content_block_gallery")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentBlockGalleryRepository")
 */
class ContentBlockGallery
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
     * @ORM\ManyToOne(targetEntity="Gallery", cascade={"persist"})
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $gallery;

    /**
     * @ORM\ManyToOne(targetEntity="ContentBlock", cascade={"persist"})
     * @ORM\JoinColumn(name="content_block_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $content_block;





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
     * Set gallery
     *
     * @param \AppBundle\Entity\Gallery $gallery
     * @return ContentBlockGallery
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;
        return $this;
    }

    /**
     * Get gallery
     *
     * @return \AppBundle\Entity\Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set content_block
     *
     * @param \AppBundle\Entity\ContentBlock $content_block
     * @return ContentBlockGallery
     */
    public function setContentBlock($content_block)
    {
        $this->content_block = $content_block;
        return $this;
    }

    /**
     * Get content_block
     *
     * @return \AppBundle\Entity\ContentBlock
     */
    public function getContentBlock()
    {
        return $this->content_block;
    }


}
