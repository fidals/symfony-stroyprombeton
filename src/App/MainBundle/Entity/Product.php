<?php

namespace App\MainBundle\Entity;

use App\MainBundle\Entity\PageTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Products
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="App\MainBundle\Entity\Repository\ProductRepository")
 */
class Product
{
    use PageTrait;
    use ImagesTrait;

    const WEB_DIR_PATH = '/../../../../web';
    const IMG_DIR_PATH = '/assets/product';
    const EMPTY_THUMB_NAME = 'prod-alt-image/prod-alt-image.png';

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="bigint", nullable=false)
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_new_price", type="boolean", nullable=true)
     */
    private $isNewPrice;

    /**
     * Это номер товара, который вбивают руками. Он состоит из кучи цифр, у каждой свой смысл в зависимости от позиции
     *
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
     * Связывает продукт с категорией, см $category
     * TODO: переименовать в category_id либо везде избавиться от этого свойства модели
     *
     * @var integer
     *
     * @ORM\Column(name="section_id", type="integer", nullable=true)
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
     * @ORM\Column(name="price", type="integer", nullable=true, options={"default" = 0})
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="string", length=250, nullable=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $category;

    /**
     * Название унаследовано из modx
     *
     * @var string
     * @ORM\Column(name="introtext", type="text", nullable=true)
     */
    private $introtext;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_text_published", type="boolean", options={"default" = 0})
     */
    private $isTextPublished = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="price_date", type="datetime")
     */
    private $datePriceUpdated;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->datePriceUpdated = new \DateTime();
    }

    /**
     * Геттер для Даты цены.
     * Если цена вообще не обновлялась, мы вернем Дату создания странички Изделия.
     * @return \DateTime
     */
    public function getDatePriceUpdated()
    {
        return $this->datePriceUpdated;
    }

    /**
     * @param \DateTime $datePriceUpdated
     */
    public function setDatePriceUpdated($datePriceUpdated)
    {
        $this->datePriceUpdated = $datePriceUpdated;
    }

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
     * @param boolean $isNewPrice
     */
    public function setIsNewPrice($isNewPrice)
    {
        $this->isNewPrice = $isNewPrice;
    }

    /**
     * @return boolean
     */
    public function getIsNewPrice()
    {
        return $this->isNewPrice;
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
        // Обновляем дату цены
        $this->setDatePriceUpdated(new \DateTime());
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Округляет цену до большей с точностью 5
     * @return int
     */
    public function getPriceRounded()
    {
        return ceil($this->price / 5) * 5;
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

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Имя переменной унаследовали из modx
     * @param string $introtext
     */
    public function setIntrotext($introtext)
    {
        $this->introtext = $introtext;
    }

    /**
     * Имя переменной унаследовали из modx
     * @return string
     */
    public function getIntrotext()
    {
        return $this->introtext;
    }

    /**
     * @return boolean
     */
    public function isIsTextPublished()
    {
        return $this->isTextPublished;
    }

    /**
     * @param boolean $isTextPublished
     */
    public function setIsTextPublished($isTextPublished)
    {
        $this->isTextPublished = $isTextPublished;
    }

    public function __toString()
    {
        return (string)$this->getName();
    }

    /**
     * Вернет true если есть картинка или false если нет
     *
     * @return bool
     */
    public function hasPicture()
    {
        return $this->getPicturePath() != self::IMG_DIR_PATH . '/' . self::EMPTY_THUMB_NAME;
    }

    public function getRouteName()
    {
        return 'app_catalog_product';
    }

    public function getRouteParameters()
    {
        if (empty($this->getId())) {
            return null;
        }

        $parameters = array('id' => $this->getId());

        return $parameters;
    }
}
