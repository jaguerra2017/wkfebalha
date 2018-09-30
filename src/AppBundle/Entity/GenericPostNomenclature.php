<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="generic_post_nomenclature")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenericPostNomenclatureRepository")
 */
class GenericPostNomenclature
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
     * @ORM\ManyToOne(targetEntity="Nomenclature", cascade={"persist"})
     * @ORM\JoinColumn(name="nomenclature_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $nomenclature;

    /**
     * @var string
     *
     * @ORM\Column(name="relation_slug", type="string", length=100, nullable=true)
     */
    private $relation_slug;







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
     * @return GenericPostNomenclature
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
     * Set nomenclature
     *
     * @param \AppBundle\Entity\Nomenclature $nomenclature
     * @return GenericPostNomenclature
     */
    public function setNomenclature($nomenclature)
    {
        $this->nomenclature = $nomenclature;
        return $this;
    }

    /**
     * Get nomenclature
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getNomenclature()
    {
        return $this->nomenclature;
    }

    /**
     * Set relation_slug
     *
     * @param string $relation_slug
     * @return GenericPostNomenclature
     */
    public function setRelationSlug($relation_slug)
    {
        $this->relation_slug = $relation_slug;
        return $this;
    }

    /**
     * Get relation_slug
     *
     * @return string
     */
    public function getRelationSlug()
    {
        return $this->relation_slug;
    }


}
