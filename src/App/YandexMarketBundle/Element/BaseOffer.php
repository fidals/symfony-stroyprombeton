<?php
namespace App\YandexMarketBundle\Element;

/**
 * В элементе offer содержится товарное предложение магазина
 * Class BaseOffer
 * @link https://yandex.ru/support/partnermarket/offers.xml
 * @package App\YandexMarketBundle\Element
 */
class BaseOffer extends AbstractElement
{
	const AGE_UNIT_YEAR = 'year';
	const AGE_UNIT_MONTH = 'month';

	/**
	 * Идентификатор предложения
	 * @var int
	 */
	protected $id;
	/**
	 * URL страницы товара
	 * @var string
	 */
	protected $url;

	/**
	 * В атрибуте available указывается статус доступности товара
	 * @var bool
	 */
	protected $available;

	/**
	 * Цена, по которой данный товар можно приобрести
	 * @var float
	 */
	protected $price;

	/**
	 * Старая цена на товар, которая обязательно должна быть выше новой цены (<price>)
	 * @var float
	 */
	protected $oldPrice;

	/**
	 * Идентификатор валюты товара
	 * @var string
	 */
	protected $currencyId;

	/**
	 * Категория товара, в которой он должен быть размещен на Яндекс.Маркете
	 * @var int
	 */
	protected $marketCategory;

	/**
	 * Массив картинок соответствующего товарного предложения. Недопустимо давать ссылку на «заглушку»,
	 * т. е. на страницу, где написано «картинка отсутствует», или на логотип магазина.
	 * @var array
	 */
	protected $pictures = array();

	/**
	 * Возможность купить соответствующий товар в розничном магазине
	 * @var bool
	 */
	protected $store;

	/**
	 * Позволяет указать возможность зарезервировать выбранный товар и забрать его самостоятельно
	 * @var bool
	 */
	protected $pickup;

	/**
	 * Производитель. Не отображается в названии предложения.
	 * @var string
	 */
	protected $vendor;

	/**
	 * Код товара (указывается код производителя)
	 * @var string
	 */
	protected $vendorCode;

	/**
	 * Описание товарного предложения. Длина текста не более 175 символов (не включая знаки препинания),
	 * запрещено использовать HTML-теги (информация внутри тегов публиковаться не будет)
	 * @var string
	 */
	protected $description;

	/**
	 * Элемент используется для отражения информации о:
	 * минимальной сумме заказа, минимальной партии товара, необходимости предоплаты (указание элемента обязательно);
	 * вариантах оплаты, описания акций и распродаж (указание элемента необязательно).
	 * Допустимая длина текста в элементе — 50 символов.
	 * @var string
	 */
	protected $salesNotes;

	/**
	 * Элемент предназначен для отметки товаров, имеющих официальную гарантию производителя.
	 * @var bool
	 */
	protected $manufacturerWarranty;

	/**
	 * Элемент предназначен для указания страны производства товара
	 * @var string
	 */
	protected $countryOfOrigin;

	/**
	 * Возрастная категория товара.
	 * годы array('unit' => 'year', 'value' => 12)
	 * месяцы array('unit' => 'month', 'value' => 6)
	 * @var array
	 */
	protected $age;

	/**
	 * Массив штрихкодов товара
	 * @var array
	 */
	protected $barcode;

	/**
	 * Для управления участием товарных предложений в программе «Заказ на Маркете»
	 * @var int
	 */
	protected $cpa;

	/**
	 * Для указания характеристик товара
	 * array('name' => ..., 'value' => ...)
	 * @link https://yandex.ru/support/partnermarket/param.xml
	 * @var array
	 */
	protected $param;

	/**
	 * Идентификатор категории
	 * @var int
	 */
	protected $categoryId;

	/**
	 * Доставка
	 * @var bool
	 */
	protected $delivery;

	/**
	 * Название продукта
	 * @var string
	 */
	protected $name;

	/**
	 * Цена доставки
	 * @var int
	 */
	protected $localDeliveryCost;

	/**
	 * Основная ставка
	 * @link https://yandex.ru/support/partnermarket/bid-cbid.xml
	 * @var int
	 */
	protected $bid;

	/**
	 * Cтавка на клик для карточек
	 * @link https://yandex.ru/support/partnermarket/bid-cbid.xml
	 * @var int
	 */
	protected $cbid;

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
	 * @param $value
	 * @param string $unit
	 */
	public function setAge($value, $unit = self::AGE_UNIT_YEAR)
	{
		$this->age = array(
			'value' => $value,
			'unit' => $unit
		);
	}

	/**
	 * @return array
	 */
	public function getAge()
	{
		return $this->age;
	}

	/**
	 * @param $available
	 */
	public function setAvailable($available)
	{
		$this->available = $available;
	}

	/**
	 * @return bool
	 */
	public function getAvailable()
	{
		return $this->available;
	}

	/**
	 * @param $barcode
	 */
	public function setBarcode($barcode)
	{
		$this->barcode = is_array($barcode) ? $barcode : array($barcode);
	}

	/**
	 * @return array
	 */
	public function getBarcode()
	{
		return $this->barcode;
	}

	/**
	 * @param $barcode
	 */
	public function addBarcode($barcode)
	{
		$this->barcode[] = $barcode;
	}

	/**
	 * @param $categoryId
	 */
	public function setCategoryId($categoryId)
	{
		$this->categoryId = $categoryId;
	}

	/**
	 * @return int
	 */
	public function getCategoryId()
	{
		return $this->categoryId;
	}

	/**
	 * @param $countryOfOrigin
	 */
	public function setCountryOfOrigin($countryOfOrigin)
	{
		$this->countryOfOrigin = $countryOfOrigin;
	}

	/**
	 * @return string
	 */
	public function getCountryOfOrigin()
	{
		return $this->countryOfOrigin;
	}

	/**
	 * @param $cpa
	 */
	public function setCpa($cpa)
	{
		$this->cpa = $cpa;
	}

	/**
	 * @return int
	 */
	public function getCpa()
	{
		return $this->cpa;
	}

	/**
	 * @param $currencyId
	 */
	public function setCurrencyId($currencyId)
	{
		$this->currencyId = $currencyId;
	}

	/**
	 * @return string
	 */
	public function getCurrencyId()
	{
		return $this->currencyId;
	}

	/**
	 * @param $delivery
	 */
	public function setDelivery($delivery)
	{
		$this->delivery = $delivery;
	}

	/**
	 * @return bool
	 */
	public function getDelivery()
	{
		return $this->delivery;
	}

	/**
	 * @param $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param $localDeliveryCost
	 */
	public function setLocalDeliveryCost($localDeliveryCost)
	{
		$this->localDeliveryCost = $localDeliveryCost;
	}

	/**
	 * @return int
	 */
	public function getLocalDeliveryCost()
	{
		return $this->localDeliveryCost;
	}

	/**
	 * @param $manufacturerWarranty
	 */
	public function setManufacturerWarranty($manufacturerWarranty)
	{
		$this->manufacturerWarranty = $manufacturerWarranty;
	}

	/**
	 * @return bool
	 */
	public function getManufacturerWarranty()
	{
		return $this->manufacturerWarranty;
	}

	/**
	 * @param $marketCategory
	 */
	public function setMarketCategory($marketCategory)
	{
		$this->marketCategory = $marketCategory;
	}

	/**
	 * @return int
	 */
	public function getMarketCategory()
	{
		return $this->marketCategory;
	}

	/**
	 * @param $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param $oldPrice
	 */
	public function setOldPrice($oldPrice)
	{
		$this->oldPrice = $oldPrice;
	}

	/**
	 * @return float
	 */
	public function getOldPrice()
	{
		return $this->oldPrice;
	}

	/**
	 * Используй addParam() для добавления параметров
	 * @param $param
	 */
	public function setParam($param)
	{
		$this->param = $param;
	}

	/**
	 * @return array
	 */
	public function getParam()
	{
		return $this->param;
	}

	/**
	 * @param $name
	 * @param $value
	 * @param null $unit
	 */
	public function addParam($name, $value, $unit = null)
	{
		$this->param[] = array(
			'name'  => $name,
			'value' => $value,
			'unit'  => $unit
		);
	}

	/**
	 * @param $pickup
	 */
	public function setPickup($pickup)
	{
		$this->pickup = $pickup;
	}

	/**
	 * @return bool
	 */
	public function getPickup()
	{
		return $this->pickup;
	}

	/**
	 * @param $picture
	 */
	public function setPictures($picture)
	{
		$this->pictures = $picture;
	}

	/**
	 * @return string
	 */
	public function getPictures()
	{
		return $this->pictures;
	}

	public function addPicture($picture)
	{
		array_push($this->pictures, $picture);
	}

	/**
	 * @param $price
	 */
	public function setPrice($price)
	{
		$this->price = $price;
	}

	/**
	 * @return float
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * @param $salesNotes
	 */
	public function setSalesNotes($salesNotes)
	{
		$this->salesNotes = $salesNotes;
	}

	/**
	 * @return string
	 */
	public function getSalesNotes()
	{
		return $this->salesNotes;
	}

	/**
	 * @param $store
	 */
	public function setStore($store)
	{
		$this->store = $store;
	}

	/**
	 * @return bool
	 */
	public function getStore()
	{
		return $this->store;
	}

	/**
	 * @param $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param $vendor
	 */
	public function setVendor($vendor)
	{
		$this->vendor = $vendor;
	}

	/**
	 * @return string
	 */
	public function getVendor()
	{
		return $this->vendor;
	}

	/**
	 * @param $vendorCode
	 */
	public function setVendorCode($vendorCode)
	{
		$this->vendorCode = $vendorCode;
	}

	/**
	 * @return string
	 */
	public function getVendorCode()
	{
		return $this->vendorCode;
	}

	/**
	 * @param int $bid
	 */
	public function setBid($bid)
	{
		$this->bid = $bid;
	}

	/**
	 * @return int
	 */
	public function getBid()
	{
		return $this->bid;
	}

	/**
	 * @param int $cbid
	 */
	public function setCbid($cbid)
	{
		$this->cbid = $cbid;
	}

	/**
	 * @return int
	 */
	public function getCbid()
	{
		return $this->cbid;
	}

	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return 'AppYandexMarket:Element:offer.xml.twig';
	}
}