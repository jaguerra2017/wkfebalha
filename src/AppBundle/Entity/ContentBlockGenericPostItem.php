<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="content_block_generic_post_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentBlockGenericPostItemRepository")
 */
class ContentBlockGenericPostItem
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
     * @ORM\ManyToOne(targetEntity="GenericPost", cascade={"persist"})
     * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $generic_post;

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
     * Set generic_post
     *
     * @param \AppBundle\Entity\GenericPost $generic_post
     * @return ContentBlockGenericPostItem
     */
    public function setGenericPost($generic_post)
    {
        $this->generic_post = $generic_post;
        return $this;
    }

    /**
     * Get generic_post
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getGenericPost()
    {
        return $this->generic_post;
    }

    /**
     * Set content_block
     *
     * @param \AppBundle\Entity\ContentBlock $content_block
     * @return ContentBlockGenericPostItem
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