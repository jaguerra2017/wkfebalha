<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="room")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoomRepository")
 */
class Room
{
    /**
     *
     * @ORM\OneToOne(targetEntity="GenericPost", cascade={"persist"})
     * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $id;

  /**
   * @ORM\ManyToOne(targetEntity="Media" , cascade={"persist"})
   * @ORM\JoinColumn(name="map_image_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
   */
  private $map_image;

  /**
   * @ORM\ManyToOne(targetEntity="GenericPost" , cascade={"persist"})
   * @ORM\JoinColumn(name="headquarter_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
   */
  private $headquarter;



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
     * @return HeadQuarter
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
     * Set mapImage
     *
     * @param \AppBundle\Entity\Media $mapImage
     *
     * @return Room
     */
    public function setMapImage(\AppBundle\Entity\Media $mapImage = null)
    {
        $this->map_image = $mapImage;
    
        return $this;
    }

    /**
     * Get mapImage
     *
     * @return \AppBundle\Entity\Media
     */
    public function getMapImage()
    {
        return $this->map_image;
    }

    /**
     * Set headquarter
     *
     * @param \AppBundle\Entity\GenericPost $headquarter
     *
     * @return Room
     */
    public function setHeadquarter(\AppBundle\Entity\GenericPost $headquarter = null)
    {
        $this->headquarter = $headquarter;
    
        return $this;
    }

    /**
     * Get headquarter
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getHeadquarter()
    {
        return $this->headquarter;
    }
}
