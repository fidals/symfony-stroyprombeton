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
	 * TODO вернуть ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alias", type="string", length=255, nullable=true)
	 */
	private $alias;

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
