<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="content_block_media")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentBlockMediaRepository")
 */
class ContentBlockMedia
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
     * @ORM\ManyToOne(targetEntity="Media", cascade={"persist"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $media;

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
     * Set media
     *
     * @param \AppBundle\Entity\Media $media
     * @return ContentBlockMedia
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * Get media
     *
     * @return \AppBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set content_block
     *
     * @param \AppBundle\Entity\ContentBlock $content_block
     * @return ContentBlockMedia
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