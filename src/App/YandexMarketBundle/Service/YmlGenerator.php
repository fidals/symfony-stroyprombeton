<?php
namespace App\YandexMarketBundle\Service;

use App\YandexMarketBundle\Element\BaseOffer;
use App\YandexMarketBundle\Element\Category;
use App\YandexMarketBundle\Element\Currency;
use App\YandexMarketBundle\Event\YmlGenerateEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Класс генератора
 * Назначение- создаёт содержимое yml файла для Yandex Market
 * Class YmlGenerator
 * @package App\YandexMarketBundle\Service
 */
class YmlGenerator
{
	/**
	 * @var \Symfony\Component\DependencyInjection\ContainerInterface
	 */
	protected $container;

	/**
	 * Категории
	 * @link https://yandex.ru/support/partnermarket/categories.xml
	 * @var array
	 */
	protected $categories;

	/**
	 * Валюты
	 * @link https://yandex.ru/support/partnermarket/currencies.xml
	 * @var array
	 */
	protected $currencies;

	/**
	 * Предложения (продукты)
	 * @link https://yandex.ru/support/partnermarket/offers.xml
	 * @var array
	 */
	protected $offers;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Генерирует содержимое yml файла
	 * @throws \Exception
	 * @return mixed
	 */
	public function generate()
	{
		$event = new YmlGenerateEvent($this);
		$this->container->get('event_dispatcher')->dispatch(YmlGenerateEvent::ON_YML_GENERATE, $event);

		if(empty($this->currencies) || empty($this->categories) || empty($this->offers)) {
			throw new \Exception("Empty currences, categories or offers provided.\nPlease, use listeners and add at least one entity of each type.");
		}

		$params = array(
			'categories'        => $this->categories,
			'currencies'        => $this->currencies,
			'offers'            => $this->offers,
			'name'              => $this->container->getParameter('app_yandex_market.shop.name'),
			'company'           => $this->container->getParameter('app_yandex_market.shop.company'),
			'url'               => 'http://' . $this->container->getParameter('app_yandex_market.shop.url'),
			'platform'          => $this->container->hasParameter('app_yandex_market.shop.platform') ? $this->container->getParameter('app_yandex_market.shop.platform') : null,
			'version'           => $this->container->hasParameter('app_yandex_market.shop.version') ? $this->container->getParameter('app_yandex_market.shop.version') : null,
			'agency'            => $this->container->hasParameter('app_yandex_market.shop.agency') ? $this->container->getParameter('app_yandex_market.shop.agency') : null,
			'email'             => $this->container->hasParameter('app_yandex_market.shop.email') ? $this->container->getParameter('app_yandex_market.shop.email') : null,
			'cpa'               => $this->container->hasParameter('app_yandex_market.shop.cpa') ? $this->container->getParameter('app_yandex_market.shop.cpa') : null,
			'deliveryCost'		=> $this->container->hasParameter('app_yandex_market.shop.delivery_options.cost') ? $this->container->getParameter('app_yandex_market.shop.delivery_options.cost') : null,
			'deliveryDays'		=> $this->container->hasParameter('app_yandex_market.shop.delivery_options.days') ? $this->container->getParameter('app_yandex_market.shop.delivery_options.days') : null
		);
		return $this->container->get('templating')->render('AppYandexMarketBundle:Default:main.xml.twig', $params);
	}

	/**
	 * Добавляет категорию в <categories> yml файла
	 * @param Category $category
	 */
	public function addCategory(Category $category)
	{
		$this->categories[] = $category->getParameters();
	}

	/**
	 * Добавляет валюту в <currencies> yml файла
	 * @param Currency $currency
	 */
	public function addCurrency(Currency $currency)
	{
		$this->currencies[] = $currency->getParameters();
	}

	/**
	 * Добавляет продукт в <offers> yml файла
	 * @param BaseOffer $offer
	 */
	public function addOffer(BaseOffer $offer)
	{
		$this->offers[] = $offer->getParameters();
	}
}