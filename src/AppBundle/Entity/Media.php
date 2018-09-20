<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaRepository")
 */
class Media
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
     * @var string
     *
     * @ORM\Column(name="name_es", type="string", length=200, nullable=true)
     */
    private $name_es;

    /**
     * @var string
     *
     * @ORM\Column(name="description_es", type="string", length=500, nullable=true)
     */
    private $description_es;

    /**
     * @ORM\ManyToOne(targetEntity="Nomenclature")
     * @ORM\JoinColumn(name="media_type_id", referencedColumnName="id")
     */
    private $media_type;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=500)
     */
    private $url;


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
        $this->name_es = null;
        $this->description_es = null;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Media
     */
    public function setName($name)
    {
        $this->name_es = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name_es;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Media
     */
    public function setDescription($description)
    {
        $this->description_es = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description_es;
    }

    /**
     * Set media_type
     *
     * @param \AppBundle\Entity\Nomenclature $media_type
     * @return Media
     */
    public function setMediaType(\AppBundle\Entity\Nomenclature $media_type)
    {
        $this->media_type = $media_type;
        return $this;
    }

    /**
     * Get media_type
     *
     * @return \AppBundle\Entity\Nomenclature
     */
    public function getNomenclature()
    {
        return $this->media_type;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Media
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set created_date
     *
     * @param \DateTime $created_date
     * @return Media
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
     * @return Media
     */
    public function setCreatedAuthor($created_author)
    {
        $this->created_author = $created_author;
        return $this;
    }

    /**
     * Get createdAuthor
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
     * @return Media
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
     * @return Media
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



    function __toString()
    {
        return $this->name_es;
    }
}