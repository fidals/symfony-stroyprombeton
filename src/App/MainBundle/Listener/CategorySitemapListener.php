<?php
namespace App\MainBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

/**
 * Собирает sitemap для категорий
 * @param SitemapPopulateEvent $event
 */
class CategorySitemapListener implements SitemapListenerInterface
{
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function populateSitemap(SitemapPopulateEvent $event)
	{
		$section = $event->getSection();
		if (is_null($section) || $section == 'category') {
			$categoryRp = $this->container->get('doctrine')->getRepository('AppMainBundle:Category');
			$router = $this->container->get('router');
			$categories = $categoryRp->findBy(array('isActive' => true));
			foreach($categories as $category) {
				$link = $router->generate('app_catalog_category', array('id' => $category->getId()), true);
				$urlObj = new UrlConcrete(
					$link,
					new \DateTime(),
					UrlConcrete::CHANGEFREQ_WEEKLY,
					0.9
				);
				$event->getGenerator()->addUrl($urlObj, 'category');
			}
		}
	}
}