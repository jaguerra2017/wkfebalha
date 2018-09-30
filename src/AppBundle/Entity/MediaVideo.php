<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="media_video")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaVideoRepository")
 */
class MediaVideo
{
    /**
     *
     * @ORM\OneToOne(targetEntity="Media", cascade={"persist"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=100, nullable=true)
     */
    private $origin;

    /**
     * @var string
     *
     * @ORM\Column(name="http_protocol", type="string", length=5, nullable=true)
     */
    private $http_protocol;




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->origin = 'youtube';
        $this->http_protocol = 'http';
    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\Media $id
     * @return MediaVideo
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return \AppBundle\Entity\Media
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set origin
     *
     * @param string $origin
     * @return MediaVideo
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set http_protocol
     *
     * @param string $http_protocol
     * @return MediaVideo
     */
    public function setHttpProtocol($http_protocol)
    {
        $this->http_protocol = $http_protocol;

        return $this;
    }

    /**
     * Get http_protocol
     *
     * @return string
     */
    public function getHttpProtocol()
    {
        return $this->http_protocol;
    }



}
