<?php
namespace App\MainBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

/**
 * Собирает sitemap для территорий
 * @param SitemapPopulateEvent $event
 */
class TerritorySitemapListener implements SitemapListenerInterface
{
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function populateSitemap(SitemapPopulateEvent $event)
	{
		$section = $event->getSection();
		if (is_null($section) || $section == 'main') {
			$territoryRp = $this->container->get('doctrine')->getRepository('AppMainBundle:Territory');
			$router = $this->container->get('router');
			$territories = $territoryRp->findBy(array('isActive' => true));
			foreach($territories as $territory) {
				$link = $router->generate('app_main_territory', array('territoryId' => $territory->getId()), true);
				$urlObj = new UrlConcrete(
					$link,
					new \DateTime(),
					UrlConcrete::CHANGEFREQ_WEEKLY,
					0.9
				);
				$event->getGenerator()->addUrl($urlObj, 'main');
			}
		}
	}
}