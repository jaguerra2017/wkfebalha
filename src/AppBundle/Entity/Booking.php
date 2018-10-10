<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingRepository")
 */
class Booking
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
   * @ORM\Column(name="name", type="string")
   */
  private $name;

  /**
   * @var string
   *
   * @ORM\Column(name="lastname", type="string")
   */
  private $lastname;

  /**
   * @var string
   *
   * @ORM\Column(name="email", type="string")
   */
  private $email;

  /**
   * @var string
   *
   * @ORM\Column(name="transaction", type="string")
   */
  private $transaction;

  /**
   * @var float
   *
   * @ORM\Column(name="total_amount", type="float")
   */
  private $totalAmount;

  /**
   * @var string
   *
   * @ORM\Column(name="notrans", type="string", nullable=true)
   */
  private $notrans;

  /**
   * @var string
   *
   * @ORM\Column(name="codig", type="string", nullable=true)
   */
  private $codig;

  /**
   * @ORM\ManyToOne(targetEntity="GenericPost")
   * @ORM\JoinColumn(name="show_id", referencedColumnName="id")
   */
  private $show;

  /**
   * @ORM\OneToMany(targetEntity="ShowSeat", mappedBy="booking",cascade={"persist","remove"})
   */
  private $seats;

  /**
   * @ORM\ManyToOne(targetEntity="Country")
   * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
   */
  private $country;

  /**
   * @ORM\ManyToOne(targetEntity="Nomenclature" )
   * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
   */
  protected $status;


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
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Booking
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Booking
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set transaction
     *
     * @param string $transaction
     *
     * @return Booking
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    
        return $this;
    }

    /**
     * Get transaction
     *
     * @return string
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Set totalAmount
     *
     * @param float $totalAmount
     *
     * @return Booking
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    
        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set show
     *
     * @param \AppBundle\Entity\GenericPost $show
     *
     * @return Booking
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
     * Add seat
     *
     * @param \AppBundle\Entity\ShowSeat $seat
     *
     * @return Booking
     */
    public function addSeat(\AppBundle\Entity\ShowSeat $seat)
    {
        $this->seats[] = $seat;
    
        return $this;
    }

    /**
     * Remove seat
     *
     * @param \AppBundle\Entity\ShowSeat $seat
     */
    public function removeSeat(\AppBundle\Entity\ShowSeat $seat)
    {
        $this->seats->removeElement($seat);
    }

    /**
     * Get seats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return Booking
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set status
     *
     * @param \AppBundle\Entity\Nomenclature $status
     *
     * @return Booking
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
     * Set notrans
     *
     * @param string $notrans
     *
     * @return Booking
     */
    public function setNotrans($notrans)
    {
        $this->notrans = $notrans;
    
        return $this;
    }

    /**
     * Get notrans
     *
     * @return string
     */
    public function getNotrans()
    {
        return $this->notrans;
    }

    /**
     * Set codig
     *
     * @param string $codig
     *
     * @return Booking
     */
    public function setCodig($codig)
    {
        $this->codig = $codig;
    
        return $this;
    }

    /**
     * Get codig
     *
     * @return string
     */
    public function getCodig()
    {
        return $this->codig;
    }
}
