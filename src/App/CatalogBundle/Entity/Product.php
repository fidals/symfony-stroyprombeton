<?php

namespace App\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\CatalogBundle\Extension\Transliteration;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="App\CatalogBundle\Entity\Repository\ProductRepository")
 */
class Product
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
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     */
    private $categoryId;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    private $file;
    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=true)
     */
    private $amount;
    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=50, nullable=true)
     */
    private $unit;
    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;
    /**
     * @var string
     *
     * @ORM\Column(name="related_product", type="string", length=255, nullable=true)
     */
    private $relatedProduct;
    /**
     * @var integer
     *
     * @ORM\Column(name="month_views", type="integer", nullable=true)
     */
    private $monthViews;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;
    /**
     * @ORM\ManyToMany(targetEntity="Device", inversedBy="products")
     * @ORM\JoinTable(name="device_product")
     */
    protected $devices;

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $category
     */
    public function setCategoryId($category)
    {
        $this->categoryId = $category;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $monthViews
     */
    public function setMonthViews($monthViews)
    {
        $this->monthViews = $monthViews;
    }

    /**
     * @return int
     */
    public function getMonthViews()
    {
        return $this->monthViews;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }



    /**
     * @param string $relatedProduct
     */
    public function setRelatedProduct($relatedProduct)
    {
        $this->relatedProduct = $relatedProduct;
    }

    /**
     * @return string
     */
    public function getRelatedProduct()
    {
        return $this->relatedProduct;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function upViews()
    {
        $this->monthViews += 1;
    }

    public function getSitemapData()
    {
        return array(
            'locData' => array(
                'route' => 'app_catalog_explore',
                'parameters' => array(
                    'catalogUnitName' => Transliteration::get($this->getName())
                )
            ),
            'priority'   => 0.9,
            'changefreq' => 'weekly'
        );
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->device = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set deviceId
     *
     * @param integer $deviceId
     * @return Product
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    /**
     * Get deviceId
     *
     * @return integer 
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * Add device
     *
     * @param \App\CatalogBundle\Entity\Device $device
     * @return Product
     */
    public function addDevice(\App\CatalogBundle\Entity\Device $device)
    {
        $this->device[] = $device;

        return $this;
    }

    /**
     * Remove device
     *
     * @param \App\CatalogBundle\Entity\Device $device
     */
    public function removeDevice(\App\CatalogBundle\Entity\Device $device)
    {
        $this->device->removeElement($device);
    }

    /**
     * Get device
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDevice()
    {
        return $this->device;
    }
    public function __toString()
    {
        return (string)$this->getName();
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
    public function getAbsolutePath()
    {
        return null === $this->imageName ? null : $this->getUploadRootDir().'/'.$this->imageName;
    }

    public function getWebPath()
    {
        return null === $this->imageName ? null : $this->getUploadDir().'/'.$this->imageName;
    }

    protected function getUploadRootDir($basepath)
    {
        return $basepath.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'bundles/catalog/img/'.Transliteration::get($this->getName());
    }

    public function upload($basepath)
    {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }

        if (null === $basepath) {
            return;
        }

        $this->file->move($this->getUploadRootDir($basepath), 'main.'.$this->getFile()->guessExtension());
        $this->file = null;
    }
    public function getFilePath()
    {
        $nameFile='bundles/catalog/img/'.Transliteration::get($this->getName()).'/main.';
        if (file_exists($nameFile.'jpg'))
        {
        return $nameFile.'jpg';
        }
        elseif (file_exists($nameFile.'png'))
        {
            return $nameFile.'png';
        }
        elseif (file_exists($nameFile.'gif'))
        {
            return $nameFile.'gif';
        }
        elseif (file_exists($nameFile.'jpeg'))
        {
            return $nameFile.'jpeg';
        }
        else
        {
            return 'bundles/catalog/empty.png';
        }
    }
    /**
     * Set file
     *
     * @param string $file
     * @return Product
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

}
