<?php

namespace App\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\CatalogBundle\Extension\Transliteration;

/**
 * Device
 *
 * @ORM\Table(name="devices")
 * @ORM\Entity
 */
class Device
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
     * @ORM\Column(name="alias", type="string", length=200, nullable=false)
     */
    private $name;
    /**
     * @var integer
     *
     * @ORM\Column(name="series_id", type="integer", nullable=false)
     */
    private $seriesId;
    /**
     * @ORM\ManyToOne(targetEntity="Series", inversedBy="device")
     * @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     */
    protected $series;
    /**
     * @var integer
     *
     * @ORM\Column(name="device_type_id", type="integer", nullable=false)
     */
    private $deviceTypeId;
    /**
     * @ORM\ManyToOne(targetEntity="DeviceType", inversedBy="devices")
     * @ORM\JoinColumn(name="device_type_id", referencedColumnName="id")
     */
    protected $deviceType;
    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="devices")
     */
    protected $products;
    public function getSitemapData()
    {
        return array(
            'locData' => array(
                'route' => 'app_catalog_explore',
                'parameters' => array(
                    'catalogUnitName' => Transliteration::get($this->getName())
                )
            ),
            'priority'   => 0.8,
            'changefreq' => 'weekly'
        );
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Device
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
     * Set seriesId
     *
     * @param integer $seriesId
     * @return Device
     */
    public function setSeriesId($seriesId)
    {
        $this->seriesId = $seriesId;

        return $this;
    }

    /**
     * Get seriesId
     *
     * @return integer 
     */
    public function getSeriesId()
    {
        return $this->seriesId;
    }

    /**
     * Set deviceTypeId
     *
     * @param integer $deviceTypeId
     * @return Device
     */
    public function setDeviceTypeId($deviceTypeId)
    {
        $this->deviceTypeId = $deviceTypeId;

        return $this;
    }

    /**
     * Get deviceTypeId
     *
     * @return integer 
     */
    public function getDeviceTypeId()
    {
        return $this->deviceTypeId;
    }

    /**
     * Set series
     *
     * @param \App\CatalogBundle\Entity\Series $series
     * @return Device
     */
    public function setSeries(\App\CatalogBundle\Entity\Series $series = null)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series
     *
     * @return \App\CatalogBundle\Entity\Series 
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set deviceType
     *
     * @param \App\CatalogBundle\Entity\DeviceType $deviceType
     * @return Device
     */
    public function setDeviceType(\App\CatalogBundle\Entity\DeviceType $deviceType = null)
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    /**
     * Get deviceType
     *
     * @return \App\CatalogBundle\Entity\DeviceType 
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Add products
     *
     * @param \App\CatalogBundle\Entity\Product $products
     * @return Device
     */
    public function addProduct(\App\CatalogBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \App\CatalogBundle\Entity\Product $products
     */
    public function removeProduct(\App\CatalogBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }
    public function __toString()
    {
        return (string)$this->getName();
    }
}
