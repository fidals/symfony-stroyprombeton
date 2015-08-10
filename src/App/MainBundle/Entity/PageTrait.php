<?php

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait для сущностей, на основе которых создаются web-страницы
 */
trait PageTrait
{
	/**
	 * свойство "name" web-страницы
	 * @var string
	 * @ORM\Column(name="name", type="string", length=255, nullable=true)
	 */
	protected $name;

	/**
	 * свойство "title" web-страницы
	 * @var string
	 * @ORM\Column(name="title", type="string", length=255, nullable=true)
	 */
	protected $title;

	/**
	 * свойство "h1" web-страницы
	 * @var string
	 * @ORM\Column(name="h1", type="string", length=255, nullable=true)
	 */
	protected $h1;

	/**
	 * свойство "keywords" web-страницы
	 * @var string
	 * @ORM\Column(name="keywords", type="string", length=255, nullable=true)
	 */
	protected $keywords;

	/**
	 * свойство "description" web-страницы
	 * @var string
	 * @ORM\Column(name="description", type="string", length=255, nullable=true)
	 */
	protected $description;

	/**
	 * флаг активности web-страницы
	 * @var bool
	 * @ORM\Column(name="is_active", type="boolean", nullable=false)
	 */
	protected $isActive = true;

	/**
	 * дата публикации web-страницы
	 * @var \DateTime
	 * @ORM\Column(name="date", type="datetime", nullable=true)
	 */
	protected $date;

	/**
	 *
	 * @var string
	 * @ORM\Column(name="text", type="text", length=5000, nullable=true)
	 */
	protected $text;

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
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
	public function getTitle()
	{
		return empty($this->title) ? $this->getName() : $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getH1()
	{
		return empty($this->h1) ? $this->getName() : $this->h1;
	}

	/**
	 * @param string $h1
	 */
	public function setH1($h1)
	{
		$this->h1 = $h1;
	}

	/**
	 * @return string
	 */
	public function getKeywords()
	{
		return $this->keywords;
	}

	/**
	 * @param string $keywords
	 */
	public function setKeywords($keywords)
	{
		$this->keywords = $keywords;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @return bool
	 */
	public function getIsActive()
	{
		return $this->isActive;
	}

	/**
	 * @param bool $isActive
	 */
	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * @param \DateTime $date
	 */
	public function setDate($date)
	{
		$this->date = $date;
	}

	/**
	 * @param string $text
	 */
	public function setText($text)
	{
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}
}