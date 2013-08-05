<?php

namespace App\CatalogBundle\Entity;

use App\CatalogBundle\Extension\Transliteration;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

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
     * @var integer
     *
     * @ORM\Column(name="ord", type="integer", nullable=true)
     */
    private $order;
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    private $description;
    /**
     * @var float
     *
     * @ORM\Column(name="coefficient", type="float", nullable=true)
     */
    private $coefficient;
    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=32, nullable=true)
     */
    private $hash;
    // Object relations
    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    protected $products;
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
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
    // Closure features needs
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

    // Closure features needs

    public function addClosure(CategoryClosure $closure)
    {
        $this->closures[] = $closure;
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

    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;
    }

    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
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
     * Add products
     *
     * @param \App\CatalogBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\App\CatalogBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \App\CatalogBundle\Entity\Product $products
     */
    public function removeProduct(\App\CatalogBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }
    public function __toString()
    {
        return (string)$this->getName();
    }
}
