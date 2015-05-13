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
	 * @ORM\Column(name="menu_title", type="string", length=255)
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
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $menuTitle
	 * @return $this
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
	 * @param string $alias
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;
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
	 */
	public function setOrd($ord)
	{
		$this->ord = $ord;
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
	 */
	public function setContent($content)
	{
		$this->content = $content;
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
