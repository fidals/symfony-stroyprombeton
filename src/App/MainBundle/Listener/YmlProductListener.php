<?php
namespace App\MainBundle\Listener;

use App\YandexMarketBundle\Element\BaseOffer;
use App\YandexMarketBundle\Element\Currency;
use App\YandexMarketBundle\Element\VendorModelOffer;
use App\YandexMarketBundle\Event\YmlGenerateEvent;
use App\YandexMarketBundle\Service\YmlListenerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Слушатель для генерации yml файла средствами YandexMarketBundle
 * Class YmlProductListener
 * @package App\MainBundle\Listener
 */
class YmlProductListener implements YmlListenerInterface
{
	/**
	 * @var \Symfony\Component\DependencyInjection\ContainerInterface
	 */
	private $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	/**
	 * @param YmlGenerateEvent $event
	 */
	public function generate(YmlGenerateEvent $event)
	{
		$products = $this
			->container
			->get('doctrine')
			->getManager()
			->getRepository('AppMainBundle:Product')
			->findBy(array('isActive' => 1));

		foreach($products as $product) {
			// если товар не имеет цены, то его не включаем в прайс для маркета
			if(!$product->getPrice()) {
				continue;
			}
			$offer = new BaseOffer();
			$offer->setId($product->getId());
			$offer->setAvailable(false);
			$offer->setPickup(true);
			$offer->setDelivery(true);
			$offer->setSalesNotes('Нужна предоплата, минимум 10 изделий');
			$offer->setStore(false);
			$offer->setManufacturerWarranty(true);
			$offer->setCountryOfOrigin('Россия');
			$offer->setCpa(1);

			if($product->hasPicture()) {
				$offer->addPicture($this->container->getParameter('base_url') . $product->getPicturePath());
			}
			empty($product->getLength()) ?: $offer->addParam('Длина', $product->getLength());
			empty($product->getWidth()) ?: $offer->addParam('Ширина', $product->getWidth());
			empty($product->getHeight()) ?: $offer->addParam('Высота', $product->getHeight());
			empty($product->getWeight()) ?: $offer->addParam('Вес', $product->getWeight());
			empty($product->getVolume()) ?: $offer->addParam('Объем', $product->getVolume());
			empty($product->getDiameterIn()) ?: $offer->addParam('Диаметр внутренний', $product->getDiameterIn());
			empty($product->getDiameterOut()) ?: $offer->addParam('Диаметр внешний', $product->getDiameterOut());
			$url = $this->container->get('router')->generate('app_catalog_product', array('id' => $product->getId()), true);
			$offer->setPrice($product->getPriceRounded());
			$offer->setUrl($url);
			$offer->setCurrencyId(Currency::ID_RUR);
			$offer->setCategoryId($product->getCategory()->getId());
			$offer->setDelivery(true);
			$offer->setName($product->getName());
			$event->getGenerator()->addOffer($offer);
		}
	}
}
