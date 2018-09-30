<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PageRepository")
 */
class Page
{
    /**
     *
     * @ORM\OneToOne(targetEntity="GenericPost", cascade={"persist"})
     * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="template_name", type="string", length=200)
     */
    private $template_name;

    /**
     * @var string
     *
     * @ORM\Column(name="template_file_name", type="string", length=500)
     */
    private $template_file_name;

    /**
     * @var string
     *
     * @ORM\Column(name="template_slug", type="string", length=200)
     */
    private $template_slug;





    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\GenericPost $id
     * @return Page
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set template_name
     *
     * @param string $template_name
     * @return Page
     */
    public function setTemplateName($template_name)
    {
        $this->template_name = $template_name;
        return $this;
    }

    /**
     * Get template_name
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->template_name;
    }

    /**
     * Set template_file_name
     *
     * @param string $template_file_name
     * @return Page
     */
    public function setTemplateFileName($template_file_name)
    {
        $this->template_file_name = $template_file_name;
        return $this;
    }

    /**
     * Get template_file_name
     *
     * @return string
     */
    public function getTemplateFileName()
    {
        return $this->template_file_name;
    }

    /**
     * Set template_slug
     *
     * @param string $template_slug
     * @return Page
     */
    public function setTemplateSlug($template_slug)
    {
        $this->template_slug = $template_slug;
        return $this;
    }

    /**
     * Get template_slug
     *
     * @return string
     */
    public function getTemplateSlug()
    {
        return $this->template_slug;
    }



}
