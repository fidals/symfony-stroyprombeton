<?php
namespace App\YandexMarketBundle\Element;

/**
 * Абстрактный элемент, реализует или нотирует базовые методы
 * Class AbstractElement
 * @package App\YandexMarketBundle\Element
 */
abstract class AbstractElement
{
	/**
	 * Возвращает все параметры элемента
	 * @return array
	 */
	public function getParameters()
	{
		return get_object_vars($this);
	}

	/**
	 * Нотирует метод, возвращающий twig для этого элемента
	 * @return mixed
	 */
	abstract public function getTemplate();
}