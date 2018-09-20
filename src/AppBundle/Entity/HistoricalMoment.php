<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="historical_moment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HistoricalMomentRepository")
 */
class HistoricalMoment
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
     * @ORM\Column(name="year", type="integer", length=4)
     */
    private $year;





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
     * @return HistoricalMoment
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
     * Set year
     *
     * @param integer $year
     * @return HistoricalMoment
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }


}