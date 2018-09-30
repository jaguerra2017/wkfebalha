<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="generic_post_taxonomy")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenericPostTaxonomyRepository")
 */
class GenericPostTaxonomy
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
     * @ORM\ManyToOne(targetEntity="Taxonomy", cascade={"persist"})
     * @ORM\JoinColumn(name="taxonomy_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $taxonomy;





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
     * @return GenericPostTaxonomy
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
     * Set taxonomy
     *
     * @param \AppBundle\Entity\Taxonomy $taxonomy
     * @return GenericPostTaxonomy
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return \AppBundle\Entity\Taxonomy
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }


}
