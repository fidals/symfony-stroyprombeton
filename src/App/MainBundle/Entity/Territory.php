<?php

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Территории
 *
 * @ORM\Table(name="territories")
 * @ORM\Entity
 */
class Territory
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
	 * @ORM\Column(name="translit_name", type="string", length=500, nullable=true)
	 */
	private $translitName;

	/**
	 * @ORM\OneToMany(targetEntity="Object", mappedBy="territory")
	 */
	protected $objects;

	/**
	 * TODO после переноса удалить сеттер
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
	 * @param string $translitName
	 */
	public function setTranslitName($translitName)
	{
		$this->translitName = $translitName;
	}

	/**
	 * @return string
	 */
	public function getTranslitName()
	{
		return $this->translitName;
	}

	/**
	 * @param $objects
	 */
	public function setObjects($objects)
	{
		$this->objects = $objects;
	}

	/**
	 * @return mixed
	 */
	public function getObjects()
	{
		return $this->objects;
	}

	/**
	 * Используется в админке
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
	}
}