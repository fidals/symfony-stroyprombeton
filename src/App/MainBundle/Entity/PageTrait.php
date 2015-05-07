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
	 * @ORM\Column(name="name", type="string", length=255, nullable=true)
	 */
	protected $name;

	/**
	 * свойство "title" web-страницы
	 * @ORM\Column(name="title", type="string", length=255, nullable=true)
	 */
	protected $title;

	/**
	 * свойство "h1" web-страницы
	 * @ORM\Column(name="h1", type="string", length=255, nullable=true)
	 */
	protected $h1;

	/**
	 * свойство "keywords" web-страницы
	 * @ORM\Column(name="keywords", type="string", length=255, nullable=true)
	 */
	protected $keywords;

	/**
	 * свойство "description" web-страницы
	 * @ORM\Column(name="description", type="string", length=255, nullable=true)
	 */
	protected $description;

	/**
	 * флаг активности web-страницы
	 * @ORM\Column(name="is_active", type="boolean", nullable=false)
	 */
	protected $isActive = true;

	/**
	 * дата публикации web-страницы
	 * @ORM\Column(name="date", type="datetime", nullable=true)
	 */
	protected $date;

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getTitle()
	{
		return $this->title ? $this->title : $this->getName();
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getH1()
	{
		return $this->h1 ? $this->h1 : $this->getTitle();
	}

	public function setH1($h1)
	{
		$this->h1 = $h1;
	}

	public function getKeywords()
	{
		return $this->keywords;
	}

	public function setKeywords($keywords)
	{
		$this->keywords = $keywords;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function getIsActive()
	{
		return $this->isActive;
	}

	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function setDate($date)
	{
		$this->date = $date;
	}
}