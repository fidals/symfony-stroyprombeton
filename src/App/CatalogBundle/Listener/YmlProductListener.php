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
			$offer = new VendorModelOffer();
			$offer->setId($product->getId());
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
