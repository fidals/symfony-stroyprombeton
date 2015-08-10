<?php
namespace App\MainBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

/**
 * Собирает sitemap для статических страниц
 * @param SitemapPopulateEvent $event
 */
class StaticPageSitemapListener implements SitemapListenerInterface
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
			$spRp = $this->container->get('doctrine')->getRepository('AppMainBundle:StaticPage');
			$router = $this->container->get('router');
			$staticPages = $spRp->findBy(array('isActive' => true));
			foreach($staticPages as $staticPage) {
				$link = $router->generate('app_main_staticpage', array('alias' => $staticPage->getAlias()), true);
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