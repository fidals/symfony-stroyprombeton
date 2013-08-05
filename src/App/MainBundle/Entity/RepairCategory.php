<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrey
 * Date: 24.03.13
 * Time: 12:55
 * To change this template use File | Settings | File Templates.
 */

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="repair_categories")
 */
class RepairCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="RepairPrice", mappedBy="price")
     */
    protected $repairPrices;

    /**
     * @ORM\ManyToOne(targetEntity="StaticPage", inversedBy="repairCategories")
     * @ORM\JoinColumn(name="page", referencedColumnName="id")
     */
    protected $page;

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
     * @return RepairCategory
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return RepairCategory
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set page
     *
     * @param \App\MainBundle\Entity\StaticPage $page
     * @return RepairCategory
     */
    public function setPage(\App\MainBundle\Entity\StaticPage $page = null)
    {
        $this->page = $page;
    
        return $this;
    }

    /**
     * Get page
     *
     * @return \App\MainBundle\Entity\StaticPage 
     */
    public function getPage()
    {
        return $this->page;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->repairPrices = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add repairPrices
     *
     * @param \App\MainBundle\Entity\RepairPrice $repairPrices
     * @return RepairCategory
     */
    public function addRepairPrice(\App\MainBundle\Entity\RepairPrice $repairPrices)
    {
        $this->repairPrices[] = $repairPrices;
    
        return $this;
    }

    /**
     * Remove repairPrices
     *
     * @param \App\MainBundle\Entity\RepairPrice $repairPrices
     */
    public function removeRepairPrice(\App\MainBundle\Entity\RepairPrice $repairPrices)
    {
        $this->repairPrices->removeElement($repairPrices);
    }

    /**
     * Get repairPrices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRepairPrices()
    {
        return $this->repairPrices;
    }
}