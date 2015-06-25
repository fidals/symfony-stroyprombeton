<?php

namespace App\CatalogBundle\Entity;

use App\MainBundle\Entity\PageTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Products
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="App\CatalogBundle\Entity\Repository\ProductRepository")
 */
class Product
{
	use PageTrait;

	const WEB_DIR_PATH = '/../../../../web';
	const IMG_DIR_PATH = '/assets/images/gbi-photos';
	const EMPTY_THUMB_NAME = 'prod-alt-image.png';

	public static $imageTypes = array(
		IMAGETYPE_JPEG,
		IMAGETYPE_JPEG2000,
		IMAGETYPE_PNG,
		IMAGETYPE_GIF
	);

	/**
	 * @var integer
	 * TODO вернуть ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(name="id", type="bigint", nullable=false)
	 * @ORM\Id
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
	 * @ORM\Column(name="price", type="integer", nullable=true)
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
	 * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
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
		$rounded = ceil($this->price / 5) * 5;
		return ($rounded % 10) == 0 ? $rounded + 5 : $rounded;
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

	/**
	 * @param string $introtext
	 */
	public function setIntrotext($introtext)
	{
		$this->introtext = $introtext;
	}

	/**
	 * @return string
	 */
	public function getIntrotext()
	{
		return $this->introtext;
	}

	public function __toString()
	{
		return (string)$this->getName();
	}

	public function getSitemapData()
	{
		return array(
			'section' => $this->getSectionId(),
			'gbi' => $this->getId(),
			'locData' => array(
				'route' => 'app_catalog_explore_category',
				'parameters' => array()
			),
			'priority' => 0.9,
			'changefreq' => 'weekly',
			'entityType' => 'product',
		);
	}

	/**
	 * Ищет все файлы с названием {id}.*
	 * Определяет тип файла, и в случае если это картинка в одном из форматов указанных в self::$imageTypes - возвращает относительный путь
	 *
	 * @return string
	 */
	public function getPicturePath()
	{
		$webPath = __DIR__ . self::WEB_DIR_PATH;
		$absPicName = $webPath . self::IMG_DIR_PATH . '/' . $this->getId();
		$gres = glob($absPicName . '.*');
		if(!empty($gres)) {
			foreach($gres as $fileName) {
				$searchResult = array_search(exif_imagetype($fileName), self::$imageTypes);
				if($searchResult !== false) {
					return self::IMG_DIR_PATH . '/' . basename($fileName);
				}
			}
		}
		return self::IMG_DIR_PATH . '/' . self::EMPTY_THUMB_NAME;
	}
}
