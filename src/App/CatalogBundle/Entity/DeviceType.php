<?php

namespace App\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\CatalogBundle\Extension\Transliteration;

/**
 * DeviceType
 *
 * @ORM\Table(name="device_types")
 * @ORM\Entity
 */
class DeviceType
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
     * @ORM\OneToMany(targetEntity="Device", mappedBy="device_types")
     */
    protected $devices;
    public function __construct()
    {
        $this->devices = new ArrayCollection();
    }
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
     * @return DeviceType
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
     * Add devices
     *
     * @param \App\CatalogBundle\Entity\Device $devices
     * @return DeviceType
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
    public function __toString()
    {
        return (string)$this->getName();
    }
}
