<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="role_functionality_action", )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleFunctionalityActionRepository")
 */
class RoleFunctionalityAction
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Nomenclature")
     * @ORM\JoinColumn(name="functionality_id", referencedColumnName="id")
     */
    private $functionality;

    /**
     * @ORM\ManyToOne(targetEntity="Nomenclature")
     * @ORM\JoinColumn(name="action_id", referencedColumnName="id", nullable=true)
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="Role" , cascade={"persist"})
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $role;




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
     * Set functionality
     *
     * @param \AppBundle\Entity\Nomenclature $functionality
     * @return RoleFunctionalityAction
     */
    public function setFunctionality(\AppBundle\Entity\Nomenclature $functionality)
    {
        $this->functionality = $functionality;

        return $this;
    }

    /**
     * Get functionality
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getFunctionality()
    {
        return $this->functionality;
    }

    /**
     * Set action
     *
     * @param \AppBundle\Entity\Nomenclature $action
     * @return RoleFunctionalityAction
     */
    public function setAction(\AppBundle\Entity\Nomenclature $action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     * @return RoleFunctionalityAction
     */
    public function setRole(\AppBundle\Entity\Role $role)
    {
        $this->role = $role;

        return $this;
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


}