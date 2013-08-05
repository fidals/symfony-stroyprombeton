<?php

namespace App\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\CatalogBundle\Extension\Transliteration;

/**
 * Series
 *
 * @ORM\Table(name="series")
 * @ORM\Entity
 */
class Series
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;
    /**
     * @ORM\OneToMany(targetEntity="Device", mappedBy="series")
     */
    protected $devices;
    public function __construct()
    {
        $this->devices = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="trademark_id", type="integer", nullable=false)
     */
    private $trademarkId;
    /**
     * @ORM\ManyToOne(targetEntity="Trademark", inversedBy="series")
     * @ORM\JoinColumn(name="trademark_id", referencedColumnName="id")
     */
    protected $trademark;
    public function getSitemapData()
    {
        return array(
            'locData' => array(
                'route' => 'app_catalog_explore',
                'parameters' => array(
                    'catalogUnitName' => Transliteration::get($this->getTitle())
                )
            ),
            'priority'   => 0.8,
            'changefreq' => 'weekly'
        );
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
     * @return Series
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
     * Set trademarkId
     *
     * @param integer $trademarkId
     * @return Series
     */
    public function setTrademarkId($trademarkId)
    {
        $this->trademarkId = $trademarkId;

        return $this;
    }

    /**
     * Get trademarkId
     *
     * @return integer 
     */
    public function getTrademarkId()
    {
        return $this->trademarkId;
    }

    /**
     * Add devices
     *
     * @param \App\CatalogBundle\Entity\Device $devices
     * @return Series
     */
    public function addDevice(\App\CatalogBundle\Entity\Device $devices)
    {
        $this->devices[] = $devices;

        return $this;
    }

    /**
     * Remove devices
     *
     * @param \App\CatalogBundle\Entity\Device $devices
     */
    public function removeDevice(\App\CatalogBundle\Entity\Device $devices)
    {
        $this->devices->removeElement($devices);
    }

    /**
     * Get devices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * Set trademark
     *
     * @param \App\CatalogBundle\Entity\Trademark $trademark
     * @return Series
     */
    public function setTrademark(\App\CatalogBundle\Entity\Trademark $trademark = null)
    {
        $this->trademark = $trademark;

        return $this;
    }

    /**
     * Get trademark
     *
     * @return \App\CatalogBundle\Entity\Trademark 
     */
    public function getTrademark()
    {
        return $this->trademark;
    }
    public function __toString()
    {
        return (string)$this->getName();
    }
}
