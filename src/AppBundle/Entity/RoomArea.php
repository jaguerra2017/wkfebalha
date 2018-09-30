<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="room_area")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoomAreaRepository")
 */
class RoomArea
{
    /**
     *
     * @ORM\OneToOne(targetEntity="GenericPost", cascade={"persist"})
     * @ORM\JoinColumn(name="generic_post_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $id;

  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GenericPost")
   * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
   */
  protected $room;


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
     * Set room
     *
     * @param \AppBundle\Entity\GenericPost $room
     *
     * @return RoomArea
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
