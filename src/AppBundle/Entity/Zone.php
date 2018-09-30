<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="zone")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ZoneRepository")
 */
class Zone
{
  /**
   *
   * @ORM\OneToOne(targetEntity="GenericPost", cascade={"persist"})
   * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
   * @ORM\Id
   */
  private $id;


  /**
   * @ORM\ManyToOne(targetEntity="Nomenclature")
   * @ORM\JoinColumn(name="nomnclature_location_id", referencedColumnName="id")
   */
  private $location;

  /**
   * @ORM\ManyToOne(targetEntity="Nomenclature")
   * @ORM\JoinColumn(name="nomnclature_orientation_id", referencedColumnName="id")
   */
  private $orientation;


  /**
   * @ORM\ManyToOne(targetEntity="GenericPost")
   * @ORM\JoinColumn(name="room_area_id", referencedColumnName="id")
   */
  private $roomArea;


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
     * Set location
     *
     * @param \AppBundle\Entity\Nomenclature $location
     *
     * @return Zone
     */
    public function setLocation(\AppBundle\Entity\Nomenclature $location = null)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set orientation
     *
     * @param \AppBundle\Entity\Nomenclature $orientation
     *
     * @return Zone
     */
    public function setOrientation(\AppBundle\Entity\Nomenclature $orientation = null)
    {
        $this->orientation = $orientation;
    
        return $this;
    }

    /**
     * Get orientation
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set roomArea
     *
     * @param \AppBundle\Entity\GenericPost $roomArea
     *
     * @return Zone
     */
    public function setRoomArea(\AppBundle\Entity\GenericPost $roomArea = null)
    {
        $this->roomArea = $roomArea;
    
        return $this;
    }

    /**
     * Get roomArea
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getRoomArea()
    {
        return $this->roomArea;
    }
}
