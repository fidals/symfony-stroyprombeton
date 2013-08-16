<?php

namespace App\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gbi
 *
 * @ORM\Table(name="gbi")
 * @ORM\Entity(repositoryClass="App\CatalogBundle\Entity\Repository\GbiRepository")
 */
class Gbi
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
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="bigint", nullable=true)
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="uri_parent", type="string", length=255, nullable=false)
     */
    private $uriParent;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_content", type="string", length=255, nullable=true)
     */
    private $nameContent;

    /**
     * @var string
     *
     * @ORM\Column(name="work_docs", type="string", length=255, nullable=true)
     */
    private $workDocs;

    /**
     * @var integer
     *
     * @ORM\Column(name="order", type="integer", nullable=true)
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="desc", type="text", nullable=true)
     */
    private $desc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_header_childs", type="boolean", nullable=true)
     */
    private $isHeaderChilds;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_have_photo", type="boolean", nullable=true)
     */
    private $isHavePhoto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="place_main_page", type="boolean", nullable=true)
     */
    private $placeMainPage;

    /**
     * @var integer
     *
     * @ORM\Column(name="external_section", type="bigint", nullable=true)
     */
    private $externalSection;

    /**
     * @var float
     *
     * @ORM\Column(name="koef_price", type="float", nullable=false)
     */
    private $koefPrice;

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
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param int $externalSection
     */
    public function setExternalSection($externalSection)
    {
        $this->externalSection = $externalSection;
    }

    /**
     * @return int
     */
    public function getExternalSection()
    {
        return $this->externalSection;
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
     * @param boolean $isHeaderChilds
     */
    public function setIsHeaderChilds($isHeaderChilds)
    {
        $this->isHeaderChilds = $isHeaderChilds;
    }

    /**
     * @return boolean
     */
    public function getIsHeaderChilds()
    {
        return $this->isHeaderChilds;
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
     * @param string $nameContent
     */
    public function setNameContent($nameContent)
    {
        $this->nameContent = $nameContent;
    }

    /**
     * @return string
     */
    public function getNameContent()
    {
        return $this->nameContent;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param boolean $placeMainPage
     */
    public function setPlaceMainPage($placeMainPage)
    {
        $this->placeMainPage = $placeMainPage;
    }

    /**
     * @return boolean
     */
    public function getPlaceMainPage()
    {
        return $this->placeMainPage;
    }

    /**
     * @param string $uriParent
     */
    public function setUriParent($uriParent)
    {
        $this->uriParent = $uriParent;
    }

    /**
     * @return string
     */
    public function getUriParent()
    {
        return $this->uriParent;
    }

    /**
     * @param string $workDocs
     */
    public function setWorkDocs($workDocs)
    {
        $this->workDocs = $workDocs;
    }

    /**
     * @return string
     */
    public function getWorkDocs()
    {
        return $this->workDocs;
    }

    /**
     * @param float $koefPrice
     */
    public function setKoefPrice($koefPrice)
    {
        $this->koefPrice = $koefPrice;
    }

    /**
     * @return float
     */
    public function getKoefPrice()
    {
        return $this->koefPrice;
    }
}
