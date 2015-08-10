<?php
namespace App\MainBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

/**
 * Собирает sitemap для новостей
 * @param SitemapPopulateEvent $event
 */
class PostSitemapListener implements SitemapListenerInterface
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
			$postRp = $this->container->get('doctrine')->getRepository('AppMainBundle:Post');
			$router = $this->container->get('router');
			$posts = $postRp->findBy(array('isActive' => true));
			foreach($posts as $post) {
				$link = $router->generate('app_main_post', array('id' => $post->getId()), true);
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