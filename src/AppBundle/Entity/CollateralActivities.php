<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="collateral_activities")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CollateralActivitiesRepository")
 */
class CollateralActivities
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
   * @ORM\Column(name="place_es", type="string", length=200, nullable=true)
   */
  private $place_es;

    /**
     * @var string
     *
     * @ORM\Column(name="place_en", type="string", length=200, nullable=true)
     */
    private $place_en;

  /**
   * @ORM\Column(name="activity_date", type="datetime")
   */
  private $actDate;

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
 
    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\GenericPost $id
     * @return CollateralActivities
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
     * Set place
     *
     * @param string $place
     * @return CollateralActivities
     */
    public function setPlace($place, $language = 'es')
    {
        eval('$this->place_'.$language.' = $place;');
        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace($language = 'es')
    {
      return eval('return $this->place_'.$language.';');
    }




    /**
     * Set placeEs
     *
     * @param string $placeEs
     *
     * @return CollateralActivities
     */
    public function setPlaceEs($placeEs)
    {
        $this->place_es = $placeEs;
    
        return $this;
    }

    /**
     * Get placeEs
     *
     * @return string
     */
    public function getPlaceEs()
    {
        return $this->place_es;
    }

    /**
     * Set placeEn
     *
     * @param string $placeEn
     *
     * @return CollateralActivities
     */
    public function setPlaceEn($placeEn)
    {
        $this->place_en = $placeEn;
    
        return $this;
    }

    /**
     * Get placeEn
     *
     * @return string
     */
    public function getPlaceEn()
    {
        return $this->place_en;
    }

    /**
     * Set actDate
     *
     * @param \DateTime $actDate
     *
     * @return CollateralActivities
     */
    public function setActDate($actDate)
    {
        $this->actDate = $actDate;
    
        return $this;
    }

    /**
     * Get actDate
     *
     * @return \DateTime
     */
    public function getActDate()
    {
        return $this->actDate;
    }

    /**
     * Set duration
     *
     * @param float $duration
     *
     * @return CollateralActivities
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    
        return $this;
    }

    /**
     * Get duration
     *
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }
}
