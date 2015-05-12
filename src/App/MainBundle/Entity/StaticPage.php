<?php

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaticPages
 *
 * @ORM\Table(name="static_pages")
 * @ORM\Entity
 */
class StaticPage
{
	use PageTrait;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alias", type="string", length=255, nullable=true)
	 */
	private $alias;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="intro_text", type="text", nullable=true)
	 */
	private $introText;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="content", type="text", nullable=true)
	 */
	private $content;

	/**
	 * Id шаблона
	 * @var integer
	 *
	 * @ORM\Column(name="template", type="integer", nullable=false)
	 */
	private $template;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="menu_title", type="string", length=255, nullable=false)
	 */
	private $menuTitle;

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
	 * @param $content
	 * @return $this
	 */
	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
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
	 * @param $introText
	 * @return $this
	 */
	public function setIntroText($introText)
	{
		$this->introText = $introText;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIntroText()
	{
		return $this->introText;
	}

	/**
	 * @param $menuTitle
	 * @return $this
	 */
	public function setMenuTitle($menuTitle)
	{
		$this->menuTitle = $menuTitle;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMenuTitle()
	{
		return $this->menuTitle;
	}

	/**
	 * @param $template
	 * @return $this
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * Для генерации sitemap
	 * @return array
	 */
	public function getSitemapData()
	{
		return array(
			'locData' => array(
				'route' => 'app_main_staticpage',
				'parameters' => array(
					'alias' => $this->getAlias(),
				)
			),
			'priority' => 0.9,
			'changefreq' => 'weekly',
			'entityType' => 'staticPage',
		);
	}
}
