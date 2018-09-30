<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="generic_post_interrelation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenericPostInterrelationRepository")
 */
class GenericPostInterrelation
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
     * @ORM\ManyToOne(targetEntity="GenericPost" , cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="GenericPost" , cascade={"persist"})
     * @ORM\JoinColumn(name="child_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $child;
    






    /**
     * Constructor
     */
    public function __construct()
    {
   
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
     * Set parent
     *
     * @param \AppBundle\Entity\GenericPost $parent
     * @return GenericPostInterrelation
     */
    public function setParent(\AppBundle\Entity\GenericPost $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set child
     *
     * @param \AppBundle\Entity\GenericPost $child
     * @return GenericPostInterrelation
     */
    public function setChild(\AppBundle\Entity\GenericPost $child)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get child
     *
     * @return \AppBundle\Entity\GenericPost
     */
    public function getChild()
    {
        return $this->child;
    }
}
