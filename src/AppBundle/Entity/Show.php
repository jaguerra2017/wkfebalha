<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tb_show")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShowRepository")
 */
class Show
{
    /**
     *
     * @ORM\OneToOne(targetEntity="GenericPost", cascade={"persist"})
     * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $id;


  /**
   * @var float
   *
   * @ORM\Column(name="seat_price", type="float")
   */
  private $seat_price;

  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GenericPost")
   * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
   */
  protected $room;

  /**
   * @ORM\Column(name="show_date", type="datetime")
   */
  private $showDate;

  /**
   * @var float
   *
   * @ORM\Column(name="duration", type="float")
   */
  private $duration;




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
     * Set seatPrice
     *
     * @param float $seatPrice
     *
     * @return Show
     */
    public function setSeatPrice($seatPrice)
    {
        $this->seat_price = $seatPrice;
    
        return $this;
    }

    /**
     * Get seatPrice
     *
     * @return float
     */
    public function getSeatPrice()
    {
        return $this->seat_price;
    }

    /**
     * Set showDate
     *
     * @param \DateTime $showDate
     *
     * @return Show
     */
    public function setShowDate($showDate)
    {
        $this->showDate = $showDate;
    
        return $this;
    }

    /**
     * Get showDate
     *
     * @return \DateTime
     */
    public function getShowDate()
    {
        return $this->showDate;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Show
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    
        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set room
     *
     * @param \AppBundle\Entity\GenericPost $room
     *
     * @return Show
     */
    public function setRoom(\AppBundle\Entity\GenericPost $room = null)
    {
        $this->room = $room;
    
        return $this;
    }

    /**
     * Get room
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getRoom()
    {
        return $this->room;
    }
}
