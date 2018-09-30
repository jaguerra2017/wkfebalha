<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="zone_row")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ZoneRowRepository")
 */
class ZoneRow
{
  /**
   *
   * @ORM\OneToOne(targetEntity="GenericPost", cascade={"persist"})
   * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
   * @ORM\Id
   */
  private $id;


  /**
   * @var integer
   *
   * @ORM\Column(name="seat_count", type="integer")
   */
  private $seatCount;

  /**
   * @var string
   *
   * @ORM\Column(name="identifier", type="string", nullable=true)
   */
  private $identifier;

  /**
   * @var integer
   *
   * @ORM\Column(name="identifier_number", type="integer", nullable=true)
   */
  private $identifierNumber;

  /**
   * @var string
   *
   * @ORM\Column(name="identifier_type", type="string")
   */
  private $identifierType;

  /**
   * @ORM\ManyToOne(targetEntity="Nomenclature")
   * @ORM\JoinColumn(name="nomnclature_orientation_id", referencedColumnName="id")
   */
  private $orientation;

  /**
   * @ORM\ManyToOne(targetEntity="Nomenclature")
   * @ORM\JoinColumn(name="nomnclature_seat_counting_id", referencedColumnName="id")
   */
  private $seatCounting;

  /**
   * @ORM\ManyToOne(targetEntity="Nomenclature")
   * @ORM\JoinColumn(name="nomnclature_seat_id", referencedColumnName="id")
   */
  private $seatNomenclature;


  /**
   * @ORM\ManyToOne(targetEntity="GenericPost")
   * @ORM\JoinColumn(name="zone_id", referencedColumnName="id")
   */
  private $zone;


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
     * Set seatCount
     *
     * @param integer $seatCount
     *
     * @return ZoneRow
     */
    public function setSeatCount($seatCount)
    {
        $this->seatCount = $seatCount;
    
        return $this;
    }

    /**
     * Get seatCount
     *
     * @return integer
     */
    public function getSeatCount()
    {
        return $this->seatCount;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     *
     * @return ZoneRow
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    
        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set orientation
     *
     * @param \AppBundle\Entity\Nomenclature $orientation
     *
     * @return ZoneRow
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
     * Set seatCounting
     *
     * @param \AppBundle\Entity\Nomenclature $seatCounting
     *
     * @return ZoneRow
     */
    public function setSeatCounting(\AppBundle\Entity\Nomenclature $seatCounting = null)
    {
        $this->seatCounting = $seatCounting;
    
        return $this;
    }

    /**
     * Get seatCounting
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getSeatCounting()
    {
        return $this->seatCounting;
    }

    /**
     * Set seatNomenclature
     *
     * @param \AppBundle\Entity\Nomenclature $seatNomenclature
     *
     * @return ZoneRow
     */
    public function setSeatNomenclature(\AppBundle\Entity\Nomenclature $seatNomenclature = null)
    {
        $this->seatNomenclature = $seatNomenclature;
    
        return $this;
    }

    /**
     * Get seatNomenclature
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getSeatNomenclature()
    {
        return $this->seatNomenclature;
    }

    /**
     * Set zone
     *
     * @param \AppBundle\Entity\GenericPost $zone
     *
     * @return ZoneRow
     */
    public function setZone(\AppBundle\Entity\GenericPost $zone = null)
    {
        $this->zone = $zone;
    
        return $this;
    }

    /**
     * Get zone
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getZone()
    {
        return $this->zone;
    }


    /**
     * Set identifierNumber
     *
     * @param integer $identifierNumber
     *
     * @return ZoneRow
     */
    public function setIdentifierNumber($identifierNumber)
    {
        $this->identifierNumber = $identifierNumber;
    
        return $this;
    }

    /**
     * Get identifierNumber
     *
     * @return integer
     */
    public function getIdentifierNumber()
    {
        return $this->identifierNumber;
    }

    /**
     * Set identifierType
     *
     * @param string $identifierType
     *
     * @return ZoneRow
     */
    public function setIdentifierType($identifierType)
    {
        $this->identifierType = $identifierType;
    
        return $this;
    }

    /**
     * Get identifierType
     *
     * @return string
     */
    public function getIdentifierType()
    {
        return $this->identifierType;
    }
}
