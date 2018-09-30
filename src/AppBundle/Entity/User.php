<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255, unique=true)
     */
    private $full_name;

    /**
     * @ORM\Column(name="user_name", type="string", length=100, unique=true)
     */
    private $user_name;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="Role" , cascade={"persist"})
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="RESTRICT")
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity="Media" , cascade={"persist"})
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id", onDelete="RESTRICT", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(name="in_use", type="boolean")
     */
    private $in_use;



    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(name="is_account_non_expired", type="boolean")
     */
    private $is_account_non_expired;

    /**
     * @ORM\Column(name="is_account_non_locked", type="boolean")
     */
    private $is_account_non_locked;

    /**
     * @ORM\Column(name="is_credential_non_expire", type="boolean")
     */
    private $is_credential_non_expire;



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
        $this->in_use = false;
        $this->avatar = null;
        $this->created_date = new \DateTime();
        $this->created_author = null;
        $this->modified_date = null;
        $this->modified_author = null;
        $this->is_active = true;
        $this->is_account_non_expired = true;
        $this->is_account_non_locked = true;
        $this->is_credential_non_expire = true;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set full_name
     *
     * @param string $full_name
     * @return User
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
        return $this;
    }

    /**
     * Get full_name
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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
     * Set avatar
     *
     * @param \AppBundle\Entity\Media $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return \AppBundle\Entity\Media
     */
    public function getAvatar()
    {
        return $this->avatar;
    }


    /**
     * Get role
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set in_use
     *
     * @param boolean $in_use
     * @return User
     */
    public function setInUse($in_use)
    {
        $this->in_use = $in_use;
        return $this;
    }

    /**
     * Get in_use
     *
     * @return boolean
     */
    public function getInUse(){
        return $this->in_use;
    }


    /**
     * Set created_date
     *
     * @param \DateTime $created_date
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * Mandatory methods/functions for extending of AdvanceUserInterface
     *
     * */

    /**
     * Set user_name
     *
     * @param string $user_name
     * @return User
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
        return $this;
    }

    /**
     * Get user_name
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->user_name;
    }


    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get roles
     *
     * @return \AppBundle\Entity\Role[]
     */
    public function getRoles()
    {
        return array($this->role->getRole());
    }


    /**
     * Set is_active
     *
     * @param boolean $is_active
     * @return User
     */
    public function setEnabled($is_active)
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->is_active;
    }


    /**
     * Set is_account_non_expired
     *
     * @param boolean $is_account_non_expired
     * @return User
     */
    public function setAccountNonExpired($is_account_non_expired)
    {
        $this->is_account_non_expired = $is_account_non_expired;

        return $this;
    }

    /**
     * Get is_account_non_expired
     *
     * @return boolean
     */
    public function isAccountNonExpired()
    {
        return $this->is_account_non_expired;
    }


    /**
     * Set is_account_non_locked
     *
     * @param boolean $is_account_non_locked
     * @return User
     */
    public function setAccountNonLocked($is_account_non_locked)
    {
        $this->is_account_non_locked = $is_account_non_locked;

        return $this;
    }

    /**
     * Get is_account_non_locked
     *
     * @return boolean
     */
    public function isAccountNonLocked()
    {
        return $this->is_account_non_locked;
    }


    /**
     * Set is_credential_non_expire
     *
     * @param boolean $is_credential_non_expire
     * @return User
     */
    public function setIsCredentialsNonExpired($is_credential_non_expire)
    {
        $this->is_credential_non_expire = $is_credential_non_expire;

        return $this;
    }

    /**
     * Get is_credential_non_expire
     *
     * @return boolean
     */
    public function isCredentialsNonExpired()
    {
        return $this->is_credential_non_expire;
    }


    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return null;
    }


    /**
     * Erase Credentials
     *
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }




    /*
     *
     * Mandatory methods/functions for extending of Serializable
     *
     * */

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->full_name,
            $this->user_name,
            $this->email,
            $this->password,
            $this->role,
            $this->avatar,
            $this->in_use,
            $this->is_active,
            $this->is_account_non_expired,
            $this->is_account_non_locked,
            $this->is_credential_non_expire
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->full_name,
            $this->user_name,
            $this->email,
            $this->password,
            $this->role,
            $this->avatar,
            $this->in_use,
            $this->is_active,
            $this->is_account_non_expired,
            $this->is_account_non_locked,
            $this->is_credential_non_expire
            ) = unserialize($serialized);
    }





   /* function __toString()
    {
        return $this->email;
    }*/



    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set isAccountNonExpired
     *
     * @param boolean $isAccountNonExpired
     *
     * @return User
     */
    public function setIsAccountNonExpired($isAccountNonExpired)
    {
        $this->is_account_non_expired = $isAccountNonExpired;
    
        return $this;
    }

    /**
     * Get isAccountNonExpired
     *
     * @return boolean
     */
    public function getIsAccountNonExpired()
    {
        return $this->is_account_non_expired;
    }

    /**
     * Set isAccountNonLocked
     *
     * @param boolean $isAccountNonLocked
     *
     * @return User
     */
    public function setIsAccountNonLocked($isAccountNonLocked)
    {
        $this->is_account_non_locked = $isAccountNonLocked;
    
        return $this;
    }

    /**
     * Get isAccountNonLocked
     *
     * @return boolean
     */
    public function getIsAccountNonLocked()
    {
        return $this->is_account_non_locked;
    }

    /**
     * Set isCredentialNonExpire
     *
     * @param boolean $isCredentialNonExpire
     *
     * @return User
     */
    public function setIsCredentialNonExpire($isCredentialNonExpire)
    {
        $this->is_credential_non_expire = $isCredentialNonExpire;
    
        return $this;
    }

    /**
     * Get isCredentialNonExpire
     *
     * @return boolean
     */
    public function getIsCredentialNonExpire()
    {
        return $this->is_credential_non_expire;
    }
}
