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
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="intro_text", type="text", nullable=true)
	 */
	private $introText;

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

	public function getRouteName()
	{
		$route = '/news/'.$this->getId().'/';

		return $route;
	}
}
