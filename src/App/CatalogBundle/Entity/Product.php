<?php

namespace App\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Products
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="App\CatalogBundle\Entity\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="new_price", type="boolean", nullable=true)
     */
    private $newPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="nomen", type="bigint", nullable=true)
     */
    private $nomen;

    /**
     * @var string
     *
     * @ORM\Column(name="mark", type="string", length=100, nullable=false)
     */
    private $mark;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="section_id", type="bigint", nullable=true)
     */
    private $sectionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="length", type="integer", nullable=true)
     */
    private $length;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer", nullable=true)
     */
    private $width;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer", nullable=true)
     */
    private $height;

    /**
     * @var float
     *
     * @ORM\Column(name="weight", type="float", nullable=true)
     */
    private $weight;

    /**
     * @var float
     *
     * @ORM\Column(name="volume", type="float", nullable=true)
     */
    private $volume;

    /**
     * @var integer
     *
     * @ORM\Column(name="diameter_out", type="integer", nullable=true)
     */
    private $diameterOut;

    /**
     * @var integer
     *
     * @ORM\Column(name="diameter_in", type="integer", nullable=true)
     */
    private $diameterIn;



    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_have_photo", type="boolean", nullable=true)
     */
    private $isHavePhoto;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="string", length=250, nullable=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $desc
     */
    public function setDescription($desc)
    {
        $this->description = $desc;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $diameterIn
     */
    public function setDiameterIn($diameterIn)
    {
        $this->diameterIn = $diameterIn;
    }

    /**
     * @return int
     */
    public function getDiameterIn()
    {
        return $this->diameterIn;
    }

    /**
     * @param int $diameterOut
     */
    public function setDiameterOut($diameterOut)
    {
        $this->diameterOut = $diameterOut;
    }

    /**
     * @return int
     */
    public function getDiameterOut()
    {
        return $this->diameterOut;
    }


    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
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
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isHavePhoto
     */
    public function setIsHavePhoto($isHavePhoto)
    {
        $this->isHavePhoto = $isHavePhoto;
    }

    /**
     * @return boolean
     */
    public function getIsHavePhoto()
    {
        return $this->isHavePhoto;
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param string $mark
     */
    public function setMark($mark)
    {
        $this->mark = $mark;
    }

    /**
     * @return string
     */
    public function getMark()
    {
        return $this->mark;
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
     * @param boolean $newPrice
     */
    public function setNewPrice($newPrice)
    {
        $this->newPrice = $newPrice;
    }

    /**
     * @return boolean
     */
    public function getNewPrice()
    {
        return $this->newPrice;
    }

    /**
     * @param int $nomen
     */
    public function setNomen($nomen)
    {
        $this->nomen = $nomen;
    }

    /**
     * @return int
     */
    public function getNomen()
    {
        return $this->nomen;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $sectionId
     */
    public function setSectionId($sectionId)
    {
        $this->sectionId = $sectionId;
    }

    /**
     * @return int
     */
    public function getSectionId()
    {
        return $this->sectionId;
    }

    /**
     * @param float $volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }

    /**
     * @return float
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getSitemapData()
    {
        return array(
            'locData' => array(
                'route' => 'app_catalog_explore_category',
                'parameters' => array(
                    'section' => $this->getSectionId(),
                    'gbi'     => $this->getId()
                )
            ),
            'priority'   => 0.9,
            'changefreq' => 'weekly'
        );
    }
}
