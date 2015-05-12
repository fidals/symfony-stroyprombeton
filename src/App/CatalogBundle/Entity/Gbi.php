<?php

namespace App\CatalogBundle\Entity;

use App\MainBundle\Entity\PageTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Gbi
 *
 * @ORM\Table(name="gbi")
 * @ORM\Entity(repositoryClass="App\CatalogBundle\Entity\Repository\GbiRepository")
 */
class Gbi
{
	use PageTrait;

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
	 * @ORM\Column(name="is_on_main_page", type="boolean", nullable=true)
	 */
	private $isOnMainPage;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="external_section", type="bigint", nullable=true)
	 */
	private $externalSection;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_coefficient", type="float", nullable=false)
	 */
	private $priceCoefficient;

	/**
	 * @param $alias
	 * @return $this
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
	 * @param $desc
	 * @return $this
	 */
	public function setDesc($desc)
	{
		$this->desc = $desc;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDesc()
	{
		return $this->desc;
	}

	/**
	 * @param $externalSection
	 * @return $this
	 */
	public function setExternalSection($externalSection)
	{
		$this->externalSection = $externalSection;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getExternalSection()
	{
		return $this->externalSection;
	}

	/**
	 * @param $id
	 * @return $this
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
	 * @param $isHavePhoto
	 * @return $this
	 */
	public function setIsHavePhoto($isHavePhoto)
	{
		$this->isHavePhoto = $isHavePhoto;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsHavePhoto()
	{
		return $this->isHavePhoto;
	}

	/**
	 * @param $isHeaderChilds
	 * @return $this
	 */
	public function setIsHeaderChilds($isHeaderChilds)
	{
		$this->isHeaderChilds = $isHeaderChilds;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsHeaderChilds()
	{
		return $this->isHeaderChilds;
	}

	/**
	 * @param $nameContent
	 * @return $this
	 */
	public function setNameContent($nameContent)
	{
		$this->nameContent = $nameContent;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNameContent()
	{
		return $this->nameContent;
	}

	/**
	 * @param $order
	 * @return $this
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
	 * @param $parentId
	 * @return $this
	 */
	public function setParentId($parentId)
	{
		$this->parentId = $parentId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * @param $isOnMainPage
	 * @return $this
	 */
	public function setIsOnMainPage($isOnMainPage)
	{
		$this->isOnMainPage = $isOnMainPage;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsOnMainPage()
	{
		return $this->isOnMainPage;
	}

	/**
	 * @param $uriParent
	 * @return $this
	 */
	public function setUriParent($uriParent)
	{
		$this->uriParent = $uriParent;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUriParent()
	{
		return $this->uriParent;
	}

	/**
	 * @param $workDocs
	 * @return $this
	 */
	public function setWorkDocs($workDocs)
	{
		$this->workDocs = $workDocs;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getWorkDocs()
	{
		return $this->workDocs;
	}

	/**
	 * @param $priceCoefficient
	 * @return $this
	 */
	public function setPriceCoefficient($priceCoefficient)
	{
		$this->priceCoefficient = $priceCoefficient;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getPriceCoefficient()
	{
		return $this->priceCoefficient;
	}
}
