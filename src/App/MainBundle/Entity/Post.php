<?php

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Posts
 *
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="App\MainBundle\Entity\Repository\PostRepository")
 */
class Post
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
	 * @ORM\Column(name="intro_text", type="text", nullable=true)
	 */
	private $introText;

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
	 * @param string $introText
	 */
	public function setIntroText($introText)
	{
		$this->introText = $introText;
	}

	/**
	 * @return string
	 */
	public function getIntroText()
	{
		return $this->introText;
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
