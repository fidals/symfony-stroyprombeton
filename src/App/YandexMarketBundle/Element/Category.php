<?php
namespace App\YandexMarketBundle\Element;

/**
 * Элемент категории
 * Class Category
 * @link https://yandex.ru/support/partnermarket/categories.xml
 * @package App\YandexMarketBundle\Element
 */
class Category extends AbstractElement
{
	/**
	 * Идентификатор категории товаров
	 * @var int
	 */
	protected $id;

	/**
	 * Идентификатор более высокой по иерархии (родительской) категории товаров
	 * @var int
	 */
	protected $parentId;

	/**
	 * Название категории
	 * @var string
	 */
	protected $title;

	/**
	 * @param $id
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
	 * @param $parentId
	 */
	public function setParentId($parentId)
	{
		$this->parentId = $parentId;
	}

	/**
	 * @return int
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * @param $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return 'AppYandexMarket:Element:category.xml.twig';
	}
}