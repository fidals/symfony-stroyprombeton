<?php
namespace App\YandexMarketBundle\Element;

/**
 * Модель vendor.model предложения
 * Class VendorModelOffer
 * @link https://yandex.ru/support/partnermarket/offers.xml
 * @package App\YandexMarketBundle\Element
 */
class VendorModelOffer extends BaseOffer
{
	/**
	 * Тип предложения
	 * @var string
	 */
	protected $type = 'vendor.model';

	/**
	 * Тип / категория товара («мобильный телефон», «стиральная машина», «угловой диван» и т.п.)
	 * @var string
	 */
	protected $typePrefix;

	/**
	 * Модель
	 * @var string
	 */
	protected $model;

	/**
	 * Товары, рекомендуемые для покупки вместе с текущим
	 * @link https://yandex.ru/support/partnermarket/rec.xml
	 * @var array
	 */
	protected $rec;

	/**
	 * Элемент предназначен для указания срока годности / срока службы либо для указания даты истечения срока годности
	 * @var string
	 */
	protected $expiry;

	/**
	 * Элемент предназначен для указания веса товара. Вес указывается в килограммах с учетом упаковки.
	 * @var int
	 */
	protected $weight;

	/**
	 * Элемент предназначен для указания габаритов товара (длина, ширина, высота) в упаковке. Размеры указываются в сантиметрах.
	 * @var array
	 */
	protected $dimensions;

	/**
	 * @param $length
	 * @param $width
	 * @param $height
	 */
	public function setDimensions($length, $width, $height)
	{
		$this->dimensions = array(
			'length' => $length,
			'width' => $width,
			'height' => $height
		);
	}

	/**
	 * @return array
	 */
	public function getDimensions()
	{
		return $this->dimensions;
	}

	/**
	 * @param $expiry
	 */
	public function setExpiry($expiry)
	{
		$this->expiry = $expiry;
	}

	/**
	 * @return string
	 */
	public function getExpiry()
	{
		return $this->expiry;
	}

	/**
	 * @param $model
	 */
	public function setModel($model)
	{
		$this->model = $model;
	}

	/**
	 * @return string
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * @param $rec
	 */
	public function setRec($rec)
	{
		$this->rec = is_array($rec) ? $rec : array($rec);
	}

	/**
	 * @param $productId
	 */
	public function addRec($productId)
	{
		$this->rec[] = $productId;
	}

	/**
	 * @return array
	 */
	public function getRec()
	{
		return $this->rec;
	}

	/**
	 * @param $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param $typePrefix
	 */
	public function setTypePrefix($typePrefix)
	{
		$this->typePrefix = $typePrefix;
	}

	/**
	 * @return string
	 */
	public function getTypePrefix()
	{
		return $this->typePrefix;
	}

	/**
	 * @param $weight
	 */
	public function setWeight($weight)
	{
		$this->weight = $weight;
	}

	/**
	 * @return int
	 */
	public function getWeight()
	{
		return $this->weight;
	}

	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return 'AppYandexMarket:Element:offer.xml.twig';
	}
}