<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrey
 * Date: 24.03.13
 * Time: 12:41
 * To change this template use File | Settings | File Templates.
 */
namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="repair_price")
 */
class RepairPrice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=True)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=True)
     */
    protected $menuName;

    /**
     * @ORM\Column(type="integer")
     */
    protected $priceMin;

    /**
     * @ORM\Column(type="integer", nullable=True)
     */
    protected $priceMax;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=True)
     */
    protected $time;

    /**
     * @ORM\Column(type="string", nullable=True)
     */
    protected $image;

    /**
     * @ORM\ManyToOne(targetEntity="RepairCategory", inversedBy="repairPrices")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="StaticPage", inversedBy="repairPrices")
     * @ORM\JoinColumn(name="page", referencedColumnName="id")
     */
    protected $page;

    /**
     * @ORM\Column(type="string", nullable=True)
     */
    protected $alias;

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
     * @return RepairPrice
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
     * Set price
     *
     * @param \int $price
     * @return RepairPrice
     */
    public function setPrice(\int $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return \int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set time
     *
     * @param float $time
     * @return RepairPrice
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return RepairPrice
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set category
     *
     * @param \App\MainBundle\Entity\Category $category
     * @return RepairPrice
     */
    public function setCategory(\App\MainBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \App\MainBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set page
     *
     * @param \App\MainBundle\Entity\StaticPage $page
     * @return RepairPrice
     */
    public function setPage(\App\MainBundle\Entity\StaticPage $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \App\MainBundle\Entity\StaticPage
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set menuName
     *
     * @param string $menuName
     * @return RepairPrice
     */
    public function setMenuName($menuName)
    {
        $this->menuName = $menuName;

        return $this;
    }

    /**
     * Get menuName
     *
     * @return string
     */
    public function getMenuName()
    {
        return $this->menuName;
    }

    /**
     * Set priceMin
     *
     * @param integer $priceMin
     * @return RepairPrice
     */
    public function setPriceMin($priceMin)
    {
        $this->priceMin = $priceMin;

        return $this;
    }

    /**
     * Get priceMin
     *
     * @return integer
     */
    public function getPriceMin()
    {
        return $this->priceMin;
    }

    /**
     * Set priceMax
     *
     * @param integer $priceMax
     * @return RepairPrice
     */
    public function setPriceMax($priceMax)
    {
        $this->priceMax = $priceMax;

        return $this;
    }

    /**
     * Get priceMax
     *
     * @return integer
     */
    public function getPriceMax()
    {
        return $this->priceMax;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return RepairPrice
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }
}