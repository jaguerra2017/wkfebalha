<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="show_seat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShowSeatRepository")
 */
class ShowSeat
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
   * @ORM\ManyToOne(targetEntity="Nomenclature" )
   * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
   */
  protected $status;

  /**
   * @var boolean
   *
   * @ORM\Column(name="avaiable", type="boolean")
   */
  private $available;

  /**
   * @ORM\ManyToOne(targetEntity="GenericPost")
   * @ORM\JoinColumn(name="seat_id", referencedColumnName="id")
   */
  private $seat;

  /**
   * @ORM\ManyToOne(targetEntity="GenericPost")
   * @ORM\JoinColumn(name="show_id", referencedColumnName="id")
   */
  private $show;

  /**
   * @ORM\ManyToOne(targetEntity="GenericPost")
   * @ORM\JoinColumn(name="booking_id", referencedColumnName="id")
   */
  private $booking;


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
    public function setAvailable($available)
    {
        $this->available = $available;
    
        return $this;
    }

    /**
     * Get avaiable
     *
     * @return boolean
     */
    public function getAvailable()
    {
        return $this->available;
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

    /**
     * Set seat
     *
     * @param \AppBundle\Entity\GenericPost $seat
     *
     * @return ShowSeat
     */
    public function setSeat(\AppBundle\Entity\GenericPost $seat = null)
    {
        $this->seat = $seat;
    
        return $this;
    }

    /**
     * Get seat
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getSeat()
    {
        return $this->seat;
    }

    /**
     * Set show
     *
     * @param \AppBundle\Entity\GenericPost $show
     *
     * @return ShowSeat
     */
    public function setShow(\AppBundle\Entity\GenericPost $show = null)
    {
        $this->show = $show;
    
        return $this;
    }

    /**
     * Get show
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Set booking
     *
     * @param \AppBundle\Entity\GenericPost $booking
     *
     * @return ShowSeat
     */
    public function setBooking(\AppBundle\Entity\GenericPost $booking = null)
    {
        $this->booking = $booking;
    
        return $this;
    }

    /**
     * Get booking
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getBooking()
    {
        return $this->booking;
    }
}
