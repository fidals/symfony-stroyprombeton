<?php

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Территории
 *
 * @ORM\Table(name="objects")
 * @ORM\Entity
 */
class Object
{
	use PageTrait;

	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * TODO после переноса вернуть ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alias", type="string", length=500, nullable=true)
	 */
	private $alias;

	/**
	 * @ORM\ManyToOne(targetEntity="Territory", inversedBy="objects")
	 * ORM\JoinColumn(name="territory_id", referencedColumnName="id")
	 */
	protected $territory;

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
	 * @param $territory
	 */
	public function setTerritory($territory)
	{
		$this->territory = $territory;
	}

	/**
	 * @return mixed
	 */
	public function getTerritory()
	{
		return $this->territory;
	}
}
