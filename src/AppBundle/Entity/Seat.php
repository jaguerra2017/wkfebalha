<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SeatRepository")
 */
class Seat
{
  /**
   *
   * @ORM\OneToOne(targetEntity="GenericPost", cascade={"persist"})
   * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
   * @ORM\Id
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity="Nomenclature" )
   * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
   */
  protected $status;

  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string")
   */
  private $name;

  /**
   * @var boolean
   *
   * @ORM\Column(name="avaiable", type="boolean")
   */
  private $avaiable;

  /**
   * @ORM\ManyToOne(targetEntity="GenericPost")
   * @ORM\JoinColumn(name="zone_row_id", referencedColumnName="id")
   */
  private $zoneRow;


  /**
   * Constructor
   */
  public function __construct()
  {
    $this->avaiable = true;
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
   * Set status
   *
   * @param \AppBundle\Entity\Nomenclature $status
   *
   * @return Seat
   */
  public function setStatus(\AppBundle\Entity\Nomenclature $status = null)
  {
    $this->status = $status;

    return $this;
  }

  /**
   * Get status
   *
   * @return \AppBundle\Entity\Nomenclature
   */
  public function getStatus()
  {
    return $this->status;
  }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Seat
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set avaiable
     *
     * @param boolean $avaiable
     *
     * @return Seat
     */
    public function setAvaiable($avaiable)
    {
        $this->avaiable = $avaiable;
    
        return $this;
    }

    /**
     * Get avaiable
     *
     * @return boolean
     */
    public function getAvaiable()
    {
        return $this->avaiable;
    }

    /**
     * Set zoneRow
     *
     * @param \AppBundle\Entity\GenericPost $zoneRow
     *
     * @return Seat
     */
    public function setZoneRow(\AppBundle\Entity\GenericPost $zoneRow = null)
    {
        $this->zoneRow = $zoneRow;
    
        return $this;
    }

    /**
     * Get zoneRow
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getZoneRow()
    {
        return $this->zoneRow;
    }
}
