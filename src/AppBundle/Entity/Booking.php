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
   * @ORM\Column(name="booking_date", type="datetime")
   */
  private $bookingDate;

  /**
   * @var boolean
   *
   * @ORM\Column(name="terms_conditions", type="boolean")
   */
  private $terms_conditions;

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
   * @ORM\JoinColumn(name="show_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
   */
  private $show;

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
    $this->terms_conditions = true;
  }

    public function getCountryName(){
      return $this->country->getCountryName();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Booking
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

    /**
     * Set id
     *
     * @param \AppBundle\Entity\GenericPost $id
     *
     * @return Booking
     */
    public function setId(\AppBundle\Entity\GenericPost $id)
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
     * Set bookingDate
     *
     * @param \DateTime $bookingDate
     *
     * @return Booking
     */
    public function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;
    
        return $this;
    }

    /**
     * Get bookingDate
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * Set termsConditions
     *
     * @param boolean $termsConditions
     *
     * @return Booking
     */
    public function setTermsConditions($termsConditions)
    {
        $this->terms_conditions = $termsConditions;
    
        return $this;
    }

    /**
     * Get termsConditions
     *
     * @return boolean
     */
    public function getTermsConditions()
    {
        return $this->terms_conditions;
    }
}
