<?php
namespace App\CatalogBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

/**
 * Собирает sitemap для продуктов
 * @param SitemapPopulateEvent $event
 */
class ProductSitemapListener implements SitemapListenerInterface
{
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function populateSitemap(SitemapPopulateEvent $event)
	{
		$section = $event->getSection();
		if (is_null($section) || $section == 'product') {
			$productRp = $this->container->get('doctrine')->getRepository('AppCatalogBundle:Product');
			$router = $this->container->get('router');
			$categories = $productRp->findBy(array('isActive' => true));
			foreach($categories as $category) {
				$link = $router->generate('app_catalog_product', array('id' => $category->getId()), true);
				$urlObj = new UrlConcrete(
					$link,
					new \DateTime(),
					UrlConcrete::CHANGEFREQ_WEEKLY,
					0.9
				);
				$event->getGenerator()->addUrl($urlObj, 'product');
			}
		}
	}
}