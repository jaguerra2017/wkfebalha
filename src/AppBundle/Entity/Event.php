<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
{
    /**
     *
     * @ORM\OneToOne(targetEntity="GenericPost", cascade={"persist"})
     * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $id;

    /**
     * @var datetime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $start_date;

    /**
     * @var datetime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $end_date;

    /**
     * @var string
     *
     * @ORM\Column(name="place_es", type="string", length=100, nullable=true)
     */
    private $place_es;




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->place_es = null;
    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\GenericPost $id
     * @return Event
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
     * Set start_date
     *
     * @param datetime $start_date
     * @return Event
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
        return $this;
    }

    /**
     * Get start_date
     *
     * @return datetime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set end_date
     *
     * @param datetime $end_date
     * @return Event
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
        return $this;
    }

    /**
     * Get end_date
     *
     * @return datetime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set place
     *
     * @param string $place
     * @return Event
     */
    public function setPlace($place)
    {
        $this->place_es = $place;
        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place_es;
    }






    /**
     * Set placeEs
     *
     * @param string $placeEs
     *
     * @return Event
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
}
