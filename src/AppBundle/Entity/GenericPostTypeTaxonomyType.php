<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="generic_post_type_taxonomy_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenericPostTypeTaxonomyTypeRepository")
 */
class GenericPostTypeTaxonomyType
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
     * @ORM\ManyToOne(targetEntity="GenericPostType", cascade={"persist"})
     * @ORM\JoinColumn(name="generic_post_type_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $generic_post_type;

    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyType", cascade={"persist"})
     * @ORM\JoinColumn(name="taxonomy_type_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $taxonomy_type;





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
     * Set generic_post_type
     *
     * @param \AppBundle\Entity\GenericPostType $generic_post_type
     * @return GenericPostTypeTaxonomyType
     */
    public function setGenericPostType($generic_post_type)
    {
        $this->generic_post_type = $generic_post_type;
        return $this;
    }

    /**
     * Get generic_post_type
     *
     * @return \AppBundle\Entity\GenericPostType
     */
    public function getGenericPostType()
    {
        return $this->generic_post_type;
    }

    /**
     * Set taxonomy_type
     *
     * @param \AppBundle\Entity\TaxonomyType $taxonomy_type
     * @return GenericPostTypeTaxonomyType
     */
    public function setTaxonomyType($taxonomy_type)
    {
        $this->taxonomy_type = $taxonomy_type;
        return $this;
    }

    /**
     * Get taxonomy_type
     *
     * @return \AppBundle\Entity\TaxonomyType
     */
    public function getTaxonomyType()
    {
        return $this->taxonomy_type;
    }


}
