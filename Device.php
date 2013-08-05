<?php

namespace App\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\CatalogBundle\Extension\Transliteration;

/**
 * Device
 *
 * @ORM\Table(name="device")
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
     * @ORM\Column(name="devicetype_id", type="integer", nullable=false)
     */
    private $devicetypeId;
    /**
     * @ORM\ManyToOne(targetEntity="Devicetype", inversedBy="device")
     * @ORM\JoinColumn(name="devicetype_id", referencedColumnName="id")
     */
    protected $devicetype;
    /**
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="device")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;
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
     * Set devicetypeId
     *
     * @param integer $devicetypeId
     * @return Device
     */
    public function setDevicetypeId($devicetypeId)
    {
        $this->devicetypeId = $devicetypeId;

        return $this;
    }

    /**
     * Get devicetypeId
     *
     * @return integer 
     */
    public function getDevicetypeId()
    {
        return $this->devicetypeId;
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
     * Set devicetype
     *
     * @param \App\CatalogBundle\Entity\Devicetype $devicetype
     * @return Device
     */
    public function setDevicetype(\App\CatalogBundle\Entity\Devicetype $devicetype = null)
    {
        $this->devicetype = $devicetype;

        return $this;
    }

    /**
     * Get devicetype
     *
     * @return \App\CatalogBundle\Entity\Devicetype 
     */
    public function getDevicetype()
    {
        return $this->devicetype;
    }

    /**
     * Add product
     *
     * @param \App\CatalogBundle\Entity\Product $product
     * @return Device
     */
    public function addProduct(\App\CatalogBundle\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \App\CatalogBundle\Entity\Product $product
     */
    public function removeProduct(\App\CatalogBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
