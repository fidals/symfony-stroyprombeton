<?php

namespace App\MainBundle\Entity;

use App\MainBundle\Entity\PageTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * Category
 *
 * @Gedmo\Tree(type="closure")
 * @Gedmo\TreeClosure(class="App\MainBundle\Entity\CategoryClosure")
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="App\MainBundle\Entity\Repository\CategoryRepository")
 */
class Category
{
	use PageTrait;

	const WEB_DIR_PATH 	   = '/../../../../web';
	const IMG_DIR_PATH     = '/assets/images/sections';
	const EMPTY_THUMB_NAME = 'logo-prozr.png';

	public static $imageTypes = array(
		IMAGETYPE_JPEG,
		IMAGETYPE_JPEG2000,
		IMAGETYPE_PNG,
		IMAGETYPE_GIF
	);

	/**
	 * @var integer
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 */
	private $id;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Category")
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
	 * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
	 */
	protected $products;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="link_to_stk_metal", type="string", length=500, nullable=true)
	 */
	private $linkToStkMetal;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="is_text_published", type="boolean", options={"default" = 0})
	 */
	private $isTextPublished = true;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="ord", type="integer", nullable=true)
	 */
	private $ord;

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
	}

	/**
	 * @return string
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	* Нужен для src/App/MainBundle/Entity/Repository/CategoryRepository.php
	 * TODO: отрефактори CategoryRepository и удали setId()
	* @param int $id
	*/
	public function setId($id)
	{
		$this->id = $id;
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
	}

	/**
	 * @return string
	 */
	public function getMark()
	{
		return $this->mark;
	}

	public function setParent(Category $parent = null)
	{
		$this->parent = $parent;
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

	/**
	 * @return int
	 */
	public function getOrd()
	{
		return $this->ord;
	}

	/**
	 * @param int $ord
	 */
	public function setOrd($ord)
	{
		$this->ord = $ord;
	}

	public function addClosure(CategoryClosure $closure)
	{
		$this->closures[] = $closure;
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
				// пытаемся определить тип картинки
				try {
					$mimetype = exif_imagetype($fileName);
				} catch(\Exception $e) {
					continue;
				}
				$searchResult = array_search($mimetype, self::$imageTypes);
				if($searchResult !== false) {
					return self::IMG_DIR_PATH . '/' . basename($fileName);
				}
			}
		}
		return self::IMG_DIR_PATH . '/' . self::EMPTY_THUMB_NAME;
	}

	public function __toString()
	{
		return (string)$this->getId() . "." . (string)$this->getTitle();
	}

	/**
	 * Метод валидации для админки
	 * @param ExecutionContext $context
	 */
	public function validate(ExecutionContext $context)
	{
		$alias = $this->getAlias();
		if(preg_match("/[^a-z0-9_\/-]/", $alias)) {
			$context->addViolation('Недопустимые для ссылки символы');
		}
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


	public function getRouteName()
	{
		return 'app_catalog_category';
	}

	public function getRouteParameters()
	{
		if (empty($this->getId())){
			return null;
		}

		$parameters = array('id' => $this->getId());

		return $parameters;
	}
}
