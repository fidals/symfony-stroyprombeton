<?php

namespace App\CatalogBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
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
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="nomen", type="integer", nullable=true)
     */
    private $nomen;

    /**
     * @Gedmo\TreeParent
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=1000, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=1000, nullable=true)
     */
    private $title;

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
     * @var integer
     *
     * @ORM\Column(name="ord", type="integer", nullable=true)
     */
    private $order;

    /**
     * @var float
     *
     * @ORM\Column(name="coefficient", type="float", nullable=false)
     */
    private $coefficient;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;


    /**
     * @var integer
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="photo_id", type="integer", nullable=true)
     */
    private $photoId;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    protected $products;

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
     * @param int $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
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
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param int $id
     */
    public function setNomen($nomen)
    {
        $this->nomen = $nomen;
        return $this;
    }

    /**
     * @return int
     */
    public function getNomen()
    {
        return $this->nomen;
    }

    /*
     * Object relations methods
     */

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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
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

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
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
     * @param int $photoId
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function addClosure(CategoryClosure $closure)
    {
        $this->closures[] = $closure;
    }

    public function getSitemapData()
    {
        return array(
            'locData' => array(
                'route' => 'app_catalog_explore_category',
                'parameters' => array(
                    'section' => $this->getId()
                )
            ),
            'priority'   => 0.9,
            'changefreq' => 'weekly'
        );
    }
    public function __toString()
    {
        return (string)$this->getId().".".(string)$this->getTitle();
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
        return null === $this->imageName ? null : '/'.$this->getUploadDir().'/'.$this->getId().'/'.$this->imageName;
    }

    public function getWebPath()
    {
        return null === $this->imageName ? null : $this->getUploadDir().'/'.$this->getId().'/'.$this->imageName;
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
        return $basePath.$this->getUploadDir();
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
        $nameFile = $this->getFileDir().$this->getId().".";
        foreach(Category::$imgExtensions as $extension){
            if (file_exists($nameFile.$extension))
                unlink($nameFile.$extension);
        }
        /*-------- двигаем файл ------*/
        $this->file->move($this->getUploadRootDir($basePath), $this->getId().".".$this->getFile()->guessExtension());
        $this->file = null;
    }

    public function rmUploaded(){
        Utils::categoryRemove($this);
    }

    public function getFilePath()
    {
        $nameFile = $this->getFileDir().$this->getId().".";
        foreach(Category::$imgExtensions as $extension){
            if (file_exists($nameFile.$extension))
                return $nameFile.$extension;
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
