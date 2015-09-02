<?php
namespace App\CatalogBundle\Listener;

use App\YandexMarketBundle\Element\Category;
use App\YandexMarketBundle\Event\YmlGenerateEvent;
use App\YandexMarketBundle\Service\YmlListenerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Слушатель для генерации yml файла средствами YandexMarketBundle
 * Class YmlCategoryListener
 * @package App\CatalogBundle\Listener
 */
class YmlCategoryListener implements YmlListenerInterface
{
	/**
	 * @var \Doctrine\ORM\EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @param EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em) {
		$this->entityManager = $em;
	}

	/**
	 * @param YmlGenerateEvent $event
	 */
	public function generate(YmlGenerateEvent $event)
	{
		$categories = $this
			->entityManager
			->getRepository('AppCatalogBundle:Category')
			->findBy(array('isActive' => 1));

		foreach($categories as $category) {
			$ymlCategory = new Category();
			$ymlCategory->setId($category->getId());
			$ymlCategory->setTitle($category->getTitle());
			if($category->getParent()) {
				$ymlCategory->setParentId($category->getParent()->getId());
			}
			$event->getGenerator()->addCategory($ymlCategory);
		}
	}
}
