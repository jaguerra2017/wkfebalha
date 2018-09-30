<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 */
class Role implements RoleInterface {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(name="slug", type="string", length=100, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(name="description", type="string", length=500)
     */
    private $description;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(name="see_site_status_offline", type="boolean")
     */
    private $see_site_status_offline;

    /**
     * @ORM\Column(name="total_users_assigned", type="integer", length=3)
     */
    private $total_users_assigned;

    /**
     * @ORM\Column(name="total_users_in_use", type="integer", length=3)
     */
    private $total_users_in_use;


    /**
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $created_date;

    /**
     * @ORM\ManyToOne(targetEntity="User" , cascade={"persist"})
     * @ORM\JoinColumn(name="created_by_user_id", referencedColumnName="id", onDelete="RESTRICT", nullable=true)
     */
    private $created_author;

    /**
     * @ORM\Column(name="modified_date", type="datetime", nullable=true)
     */
    private $modified_date;

    /**
     * @ORM\ManyToOne(targetEntity="User" , cascade={"persist"})
     * @ORM\JoinColumn(name="modified_by_user_id", referencedColumnName="id", onDelete="RESTRICT", nullable=true)
     */
    private $modified_author;







    /**
     * Constructor
     */
    public function __construct()
    {
        $this->see_site_status_offline = false;
        $this->is_active = true;
        $this->total_users_assigned = 0;
        $this->total_users_in_use = 0;
        $this->created_date = new \DateTime();
        $this->created_author = null;
        $this->modified_date = null;
        $this->modified_author = null;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Role
     */
    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }


    /**
     * Set description
     *
     * @param string $description
     * @return Role
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }


    /**
     * Set is_active
     *
     * @param boolean $is_active
     * @return Role
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean
     */
    public function getIsActive(){
        return $this->is_active;
    }

    /**
     * Set see_site_status_offline
     *
     * @param boolean $see_site_status_offline
     * @return Role
     */
    public function setSeeSiteStatusOffline($see_site_status_offline)
    {
        $this->see_site_status_offline = $see_site_status_offline;
        return $this;
    }

    /**
     * Get see_site_status_offline
     *
     * @return boolean
     */
    public function geSeeSiteStatusOffline(){
        return $this->see_site_status_offline;
    }


    /**
     * Set total_users_assigned
     *
     * @param integer $total_users_assigned
     * @return Role
     */
    public function setTotalUsersAssigned($total_users_assigned)
    {
        $this->total_users_assigned = $total_users_assigned;
        return $this;
    }

    /**
     * Get total_users_assigned
     *
     * @return integer
     */
    public function getTotalUsersAssigned(){
        return $this->total_users_assigned;
    }


    /**
     * Set total_users_in_use
     *
     * @param integer $total_users_in_use
     * @return Role
     */
    public function setTotalUsersInUse($total_users_in_use)
    {
        $this->total_users_in_use = $total_users_in_use;
        return $this;
    }

    /**
     * Get total_users_in_use
     *
     * @return integer
     */
    public function getTotalUsersInUse(){
        return $this->total_users_in_use;
    }


    /**
     * Set created_date
     *
     * @param \DateTime $created_date
     * @return Role
     */
    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;
        return $this;
    }

    /**
     * Get created_date
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Set created_author
     *
     * @param \AppBundle\Entity\User $created_author
     * @return Role
     */
    public function setCreatedAuthor($created_author)
    {
        $this->created_author = $created_author;
        return $this;
    }

    /**
     * Get created_author
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedAuthor()
    {
        return $this->created_author;
    }

    /**
     * Set modified_date
     *
     * @param \DateTime $modified_date
     * @return Role
     */
    public function setModifiedDate($modified_date)
    {
        $this->modified_date = $modified_date;
        return $this;
    }

    /**
     * Get modified_date
     *
     * @return \DateTime
     */
    public function getModifiedDate()
    {
        return $this->modified_date;
    }

    /**
     * Set modified_author
     *
     * @param \AppBundle\Entity\User $modified_author
     * @return Role
     */
    public function setModifiedAuthor($modified_author)
    {
        $this->modified_author = $modified_author;
        return $this;
    }

    /**
     * Get modified_author
     *
     * @return \AppBundle\Entity\User
     */
    public function getModifiedAuthor()
    {
        return $this->modified_author;
    }




    /*
     *
     * Mandatory methods/functions for extending of RoleInterface
     *
     * */

    public function getRole(){
        return $this->slug;
    }





    /*function __toString()
    {
        return $this->name;
    }*/



    /**
     * Get seeSiteStatusOffline
     *
     * @return boolean
     */
    public function getSeeSiteStatusOffline()
    {
        return $this->see_site_status_offline;
    }
}
