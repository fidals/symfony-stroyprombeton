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
class YmlCurrencyListener implements YmlListenerInterface
{
	/**
	 * @param YmlGenerateEvent $event
	 */
	public function generate(YmlGenerateEvent $event)
	{
		$currency = new Currency();
		$currency->setId(Currency::ID_RUR);
		$currency->setRate(1);
		$event->getGenerator()->addCurrency($currency);
	}
}
