<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nom_content_block_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NomContentBlockTypeRepository")
 */
class NomContentBlockType
{
    /**
     *
     * @ORM\OneToOne(targetEntity="Nomenclature")
     * @ORM\JoinColumn(name="nom_content_block_id", referencedColumnName="id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_reusable", type="boolean")
     */
    private $is_reusable;





    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\Nomenclature $id
     * @return NomContentBlockType
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
     * Set is_reusable
     *
     * @param boolean $is_reusable
     * @return NomContentBlockType
     */
    public function setIsReusable($is_reusable)
    {
        $this->is_reusable = $is_reusable;

        return $this;
    }

    /**
     * Get is_reusable
     *
     * @return boolean
     */
    public function isReusable()
    {
        return $this->is_reusable;
    }



    /**
     * Get isReusable
     *
     * @return boolean
     */
    public function getIsReusable()
    {
        return $this->is_reusable;
    }
}
