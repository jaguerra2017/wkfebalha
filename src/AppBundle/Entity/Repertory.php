<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="repertory")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RepertoryRepository")
 */
class Repertory
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
     * @ORM\Column(name="choreography", type="string", length=100, nullable=true)
     */
    private $choreography;

    /**
     * @var string
     *
     * @ORM\Column(name="music", type="string", length=100, nullable=true)
     */
    private $music;

    /**
     * @var string
     *
     * @ORM\Column(name="scenery", type="string", length=100, nullable=true)
     */
    private $scenery;

    /**
     * @var string
     *
     * @ORM\Column(name="apparel", type="string", length=100, nullable=true)
     */
    private $apparel;

    /**
     * @var string
     *
     * @ORM\Column(name="production", type="string", length=100, nullable=true)
     */
    private $production;




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->choreography = null;
        $this->music = null;
        $this->scenery = null;
        $this->apparel = null;
        $this->production = null;
    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\GenericPost $id
     * @return Repertory
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
     * Set choreography
     *
     * @param string $choreography
     * @return Repertory
     */
    public function setChoreography($choreography)
    {
        $this->choreography = $choreography;
        return $this;
    }

    /**
     * Get choreography
     *
     * @return string
     */
    public function getChoreography()
    {
        return $this->choreography;
    }

    /**
     * Set music
     *
     * @param string $music
     * @return Repertory
     */
    public function setMusic($music)
    {
        $this->music = $music;
        return $this;
    }

    /**
     * Get music
     *
     * @return string
     */
    public function getMusic()
    {
        return $this->music;
    }

    /**
     * Set scenery
     *
     * @param string $scenery
     * @return Repertory
     */
    public function setScenery($scenery)
    {
        $this->scenery = $scenery;
        return $this;
    }

    /**
     * Get scenery
     *
     * @return string
     */
    public function getScenery()
    {
        return $this->scenery;
    }

    /**
     * Set apparel
     *
     * @param string $apparel
     * @return Repertory
     */
    public function setApparel($apparel)
    {
        $this->apparel = $apparel;
        return $this;
    }

    /**
     * Get apparel
     *
     * @return string
     */
    public function getApparel()
    {
        return $this->apparel;
    }

    /**
     * Set production
     *
     * @param string $production
     * @return Repertory
     */
    public function setProduction($production)
    {
        $this->production = $production;
        return $this;
    }

    /**
     * Get production
     *
     * @return string
     */
    public function getProduction()
    {
        return $this->production;
    }





}