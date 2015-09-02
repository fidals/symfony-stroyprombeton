<?php
namespace App\YandexMarketBundle\Element;

/**
 * Элемент currency задает курс валют магазина. Каждая из валют описывается отдельным элементом
 * Class Currency
 * @link https://yandex.ru/support/partnermarket/currencies.xml
 * @package App\YandexMarketBundle\Element
 */
class Currency extends AbstractElement
{
	/**
	 * Курс по Центральному банку РФ
	 */
	const RATE_CBRF = 'CBRF';

	/**
	 * Курс по Национальному банку Украины
	 */
	const RATE_NBU = 'NBU';

	/**
	 * Курс по Национальному банку Казахстана
	 */
	const RATE_NBK = 'NBK';

	/**
	 * Курс по банку той страны, к которой относится магазин по своему региону, указанному в партнерском интерфейсе
	 */
	const RATE_NB = 'NB';

	const ID_RUR = 'RUR';
	const ID_USD = 'USD';
	const ID_EUR = 'EUR';
	const ID_UAH = 'UAH';
	const ID_KZT = 'KZT';
	const ID_BYR = 'BYR';

	/**
	 * Параметр id элемента <currency> указывает код одной или нескольких валют, которые могут быть использованы в YML-файле
	 * @var string
	 */
	protected $id;

	/**
	 * Параметр rate указывает курс валюты к курсу основной валюты, взятой за единицу (валюта, для которой rate="1")
	 * @var
	 */
	protected $rate;

	/**
	 * Параметр plus указывает количество процентов, на которое отличается от курса
	 * @var int
	 */
	protected $plus;

	/**
	 * @param $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param $plus
	 */
	public function setPlus($plus)
	{
		$this->plus = $plus;
	}

	/**
	 * @return int
	 */
	public function getPlus()
	{
		return $this->plus;
	}

	/**
	 * @param $rate
	 */
	public function setRate($rate)
	{
		$this->rate = $rate;
	}

	/**
	 * @return mixed
	 */
	public function getRate()
	{
		return $this->rate;
	}

	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return 'AppYandexMarket:Element:currency.xml.twig';
	}
}