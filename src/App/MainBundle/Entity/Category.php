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
    use ImagesTrait;

    const WEB_DIR_PATH = '/../../../../web';
    const IMG_DIR_PATH = '/assets/category';
    const EMPTY_THUMB_NAME = 'logo-prozr/logo-prozr.png';
    const STK_METAL_URL = 'http://www.stk-metal.ru/metallokonstruktsii/';

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

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Метод валидации для админки
     * @param ExecutionContext $context
     */
    public function validate(ExecutionContext $context)
    {
        $alias = $this->getAlias();
        if (preg_match("/[^a-z0-9_\/-]/", $alias)) {
            $context->addViolation('Недопустимые для ссылки символы');
        }
    }

    public function getRouteParameters()
    {
        if (empty($this->getId())) {
            return null;
        }

        $parameters = array('id' => $this->getId());

        return $parameters;
    }

    /**
     * Геттер для XML с линкованной категорией с Металла.
     *
     * @return null|\SimpleXMLElement
     */
    public function getMetalXML()
    {
        if ($this->linkToStkMetal) {
            return new \SimpleXMLElement(self::STK_METAL_URL . $this->getLinkToStkMetal() . '/get-xml-data/', null, true);
        }

        return null;
    }
}
