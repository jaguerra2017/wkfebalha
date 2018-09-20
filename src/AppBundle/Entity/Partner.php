<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="partner")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PartnerRepository")
 */
class Partner
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
     * @ORM\Column(name="address", type="string", length=200, nullable=true)
     */
    private $address;






    /**
     * Constructor
     */
    public function __construct()
    {
        $this->address = null;
    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\GenericPost $id
     * @return Partner
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
     * Set address
     *
     * @param string $address
     * @return Partner
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }



}