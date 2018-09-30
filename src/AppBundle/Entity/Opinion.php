<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="opinion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OpinionRepository")
 */
class Opinion
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
     * @ORM\Column(name="reference_es", type="string", length=255)
     */
    private $reference_es;





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
     * @return Opinion
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
     * Set reference
     *
     * @param string $reference
     * @return Opinion
     */
    public function setReference($reference)
    {
        $this->reference_es = $reference;
        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference_es;
    }




    /**
     * Set referenceEs
     *
     * @param string $referenceEs
     *
     * @return Opinion
     */
    public function setReferenceEs($referenceEs)
    {
        $this->reference_es = $referenceEs;
    
        return $this;
    }

    /**
     * Get referenceEs
     *
     * @return string
     */
    public function getReferenceEs()
    {
        return $this->reference_es;
    }
}
