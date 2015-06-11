<?php

namespace App\CatalogBundle\Entity;

use App\MainBundle\Entity\PageTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\CatalogBundle\Extension\Utils;

/**
 * Category
 *
 * @Gedmo\Tree(type="closure")
 * @Gedmo\TreeClosure(class="App\CatalogBundle\Entity\CategoryClosure")
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="App\CatalogBundle\Entity\Repository\CategoryRepository")
 */
class Category
{
	use PageTrait;

	const WEB_DIR_PATH = '/../../../../web';
	const IMG_DIR_PATH = '/assets/images/sections';
	const IMG_GAP_NAME = 'logo-prozr.png';

	/**
	 * TODO Вернуть ORM\GeneratedValue(strategy="IDENTITY")
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 */
	private $id;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
	 */
	private $parent;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alias", type="string", length=1000, nullable=true)
	 */
	private $alias;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="mark", type="string", length=1000, nullable=true)
	 */
	private $mark;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="coefficient", type="float", nullable=false)
	 */
	private $coefficient;

	/**
	 * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
	 */
	protected $products;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="link_to_stk_metal", type="string", length=500, nullable=true)
	 */
	private $linkToStkMetal;

	public function __construct()
	{
		$this->products = new ArrayCollection();
	}

	/**
	 * @param string $alias
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	public function setProducts($products)
	{
		$this->products = $products;
	}

	public function getProducts()
	{
		return $this->products;
	}

	/**
	 * @param string $mark
	 */
	public function setMark($mark)
	{
		$this->mark = $mark;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMark()
	{
		return $this->mark;
	}

	/**
	 * @param float $coefficient
	 */
	public function setCoefficient($coefficient)
	{
		$this->coefficient = $coefficient;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getCoefficient()
	{
		return $this->coefficient;
	}

	public function setParent(Category $parent = null)
	{
		$this->parent = $parent;
		return $this;
	}

	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @param string $linkToStkMetal
	 */
	public function setLinkToStkMetal($linkToStkMetal)
	{
		$this->linkToStkMetal = $linkToStkMetal;
	}

	/**
	 * @return string
	 */
	public function getLinkToStkMetal()
	{
		return $this->linkToStkMetal;
	}

	public function addClosure(CategoryClosure $closure)
	{
		$this->closures[] = $closure;
	}

	public function getSitemapData()
	{
		return array(
			'section' => $this->getId(),
			'locData' => array(
				'route' => 'app_catalog_explore_category',
				'parameters' => array()
			),
			'priority' => 0.9,
			'changefreq' => 'weekly',
			'entityType' => 'category',
		);
	}

	public function getPicturePath()
	{
		$webPath = __DIR__ . self::WEB_DIR_PATH;
		$webFilePath = self::IMG_DIR_PATH . '/' . $this->getId() . '.png';
		$picturePath = $webPath . $webFilePath;
		if(file_exists($picturePath)) {
			return $webFilePath;
		} else {
			return self::IMG_DIR_PATH . '/' . self::IMG_GAP_NAME;
		}
	}

	public function __toString()
	{
		return (string)$this->getId() . "." . (string)$this->getTitle();
	}

	/* ----------- Блок кода для файла. Нужен для админки. Очень понавательный. -------------- */
	private $file;
	/**
	 * @var string
	 * Папка для изображений. Почему она static написано в матчасти.
	 * Без переднего слэша, чтобы бэкэнду была понятна папка.
	 * На фронте прямо в твиге сандалим слешик.
	 */
	public static $defaultDirForImg = 'bundles/catalog/img/categories/';

	public static $imgExtensions = array('png', 'jpg', 'jpeg', 'bmp', 'gif', 'tiff');

	public function getAbsolutePath()
	{
		return null === $this->imageName ? null : '/' . $this->getUploadDir() . '/' . $this->getId() . '/' . $this->imageName;
	}

	public function getWebPath()
	{
		return null === $this->imageName ? null : $this->getUploadDir() . '/' . $this->getId() . '/' . $this->imageName;
	}

	protected function getUploadDir()
	{
		return Category::$defaultDirForImg;
	}

	protected function getFileDir()
	{
		return Category::$defaultDirForImg;
	}

	protected function getUploadRootDir($basePath)
	{
		return $basePath . $this->getUploadDir();
	}

	public function upload($basePath)
	{
		// the file property can be empty if the field is not required
		if (null === $this->file) {
			return;
		}

		if (null === $basePath) {
			return;
		}

		// we use the original file name here but you should
		// sanitize it at least to avoid any security issues

		/*-------- проверяем на наличие других файлов main в папке и удаляем если есть ------*/
		$nameFile = $this->getFileDir() . $this->getId() . ".";
		foreach (Category::$imgExtensions as $extension) {
			if (file_exists($nameFile . $extension))
				unlink($nameFile . $extension);
		}
		/*-------- двигаем файл ------*/
		$this->file->move($this->getUploadRootDir($basePath), $this->getId() . "." . $this->getFile()->guessExtension());
		$this->file = null;
	}

	public function rmUploaded()
	{
		Utils::categoryRemove($this);
	}

	public function getFilePath()
	{
		$nameFile = $this->getFileDir() . $this->getId() . ".";
		foreach (Category::$imgExtensions as $extension) {
			if (file_exists($nameFile . $extension))
				return $nameFile . $extension;
		}

		return 'bundles/catalog/empty.png';
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
	/* ----------- Код для файла кончился -------------- */
}
