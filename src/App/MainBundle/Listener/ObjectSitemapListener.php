<?php
namespace App\MainBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

/**
 * Собирает sitemap для объектов
 * @param SitemapPopulateEvent $event
 */
class ObjectSitemapListener implements SitemapListenerInterface
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
			$objectRp = $this->container->get('doctrine')->getRepository('AppMainBundle:Object');
			$router = $this->container->get('router');
			$objects = $objectRp->findBy(array('isActive' => true));
			foreach($objects as $object) {
				$link = $router->generate('app_main_object', array('id' => $object->getId()), true);
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