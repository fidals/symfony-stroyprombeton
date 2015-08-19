<?php
namespace App\CatalogBundle\Listener;

use App\YandexMarketBundle\Element\BaseOffer;
use App\YandexMarketBundle\Element\Currency;
use App\YandexMarketBundle\Element\VendorModelOffer;
use App\YandexMarketBundle\Event\YmlGenerateEvent;
use App\YandexMarketBundle\Service\YmlListenerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Слушатель для генерации yml файла средствами YandexMarketBundle
 * Class YmlProductListener
 * @package App\CatalogBundle\Listener
 */
class YmlProductListener implements YmlListenerInterface
{
	/**
	 * @var \Doctrine\ORM\EntityManagerInterface
	 */
	private $entityManager;
	/**
	 * @var \Symfony\Component\Routing\RouterInterface
	 */
	private $router;

	public function __construct(EntityManagerInterface $em, RouterInterface $router) {
		$this->entityManager = $em;
		$this->router = $router;
	}

	/**
	 * @param YmlGenerateEvent $event
	 */
	public function generate(YmlGenerateEvent $event)
	{
		$products = $this
			->entityManager
			->getRepository('AppCatalogBundle:Product')
			->findBy(array('isActive' => 1));

		foreach($products as $product) {
			$offer = new BaseOffer();
			$offer->setId($product->getId());
			$offer->setAvailable(false);
			$offer->setPickup(true);
			$offer->setDelivery(true);
			$offer->setSalesNotes('Детали оплаты и доставки уточните с менеджером.');

			!$product->hasPicture() ?: $offer->setPicture($product->getPicturePath());
			is_null($product->getLength()) ?: $offer->addParam('Длина', $product->getLength());
			is_null($product->getWidth()) ?: $offer->addParam('Ширина', $product->getWidth());
			is_null($product->getHeight()) ?: $offer->addParam('Высота', $product->getHeight());
			is_null($product->getWeight()) ?: $offer->addParam('Вес', $product->getWeight());
			is_null($product->getVolume()) ?: $offer->addParam('Объем', $product->getVolume());
			is_null($product->getDiameterIn()) ?: $offer->addParam('Диаметр внутренний', $product->getDiameterIn());
			is_null($product->getDiameterOut()) ?: $offer->addParam('Диаметр внешний', $product->getDiameterOut());
			$url = $this->router->generate('app_catalog_product', array('id' => $product->getId()), true);
			$offer->setUrl($url);
			$offer->setPrice($product->getPrice());
			$offer->setCurrencyId(Currency::ID_RUR);
			$offer->setCategoryId($product->getCategory()->getId());
			$offer->setDelivery(true);
			$offer->setName($product->getName());
			$event->getGenerator()->addOffer($offer);
		}
	}
}
