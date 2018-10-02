<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="headquarter")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HeadQuarterRepository")
 */
class HeadQuarter
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
   * @ORM\Column(name="address_es", type="string", length=200, nullable=true)
   */
  private $address_es;

    /**
     * @var string
     *
     * @ORM\Column(name="address_en", type="string", length=200, nullable=true)
     */
    private $address_en;

  /**
   * @var boolean
   *
   * @ORM\Column(name="online_sale", type="boolean")
   */
  private $online_sale;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->address = null;
        $this->online_sale = true;
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
     * Set address
     *
     * @param string $address
     * @return HeadQuarter
     */
    public function setAddress($address, $language = 'es')
    {
        eval('$this->address_'.$language.' = $address;');
        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress($language = 'es')
    {
      return eval('return $this->address_'.$language.';');
    }




    /**
     * Set addressEs
     *
     * @param string $addressEs
     *
     * @return HeadQuarter
     */
    public function setAddressEs($addressEs)
    {
        $this->address_es = $addressEs;
    
        return $this;
    }

    /**
     * Get addressEs
     *
     * @return string
     */
    public function getAddressEs()
    {
        return $this->address_es;
    }

    /**
     * Set addressEn
     *
     * @param string $addressEn
     *
     * @return HeadQuarter
     */
    public function setAddressEn($addressEn)
    {
        $this->address_en = $addressEn;
    
        return $this;
    }

    /**
     * Get addressEn
     *
     * @return string
     */
    public function getAddressEn()
    {
        return $this->address_en;
    }

    /**
     * Set onlineSale
     *
     * @param boolean $onlineSale
     *
     * @return HeadQuarter
     */
    public function setOnlineSale($onlineSale)
    {
        $this->online_sale = $onlineSale;
    
        return $this;
    }

    /**
     * Get onlineSale
     *
     * @return boolean
     */
    public function getOnlineSale()
    {
        return $this->online_sale;
    }
}
