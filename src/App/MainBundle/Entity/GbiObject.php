<?php

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Объекты, на которые мы поставляли ЖБИ.
 * Есть раздел на сайте
 *
 * @ORM\Table(name="gbi_objects")
 * @ORM\Entity
 */
class GbiObject
{
	use PageTrait;

	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="menuTitle", type="string", length=255)
	 */
	private $menuTitle;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alias", type="string", length=255)
	 */
	private $alias;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ord", type="integer")
	 */
	private $ord;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="content", type="text", nullable=true)
	 */
	private $content;

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
	 * Set menuTitle
	 *
	 * @param string $menuTitle
	 * @return GbiObject
	 */
	public function setMenuTitle($menuTitle)
	{
		$this->menuTitle = $menuTitle;
		return $this;
	}

	/**
	 * Get menuTitle
	 *
	 * @return string
	 */
	public function getMenuTitle()
	{
		return $this->menuTitle;
	}

	/**
	 * Set alias
	 *
	 * @param string $alias
	 * @return GbiObject
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

	/**
	 * Set ord
	 *
	 * @param integer $ord
	 * @return GbiObject
	 */
	public function setOrd($ord)
	{
		$this->ord = $ord;

		return $this;
	}

	/**
	 * Get ord
	 *
	 * @return integer
	 */
	public function getOrd()
	{
		return $this->ord;
	}

	/**
	 * Set content
	 *
	 * @param string $content
	 * @return GbiObject
	 */
	public function setContent($content)
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * Get content
	 *
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}
}
