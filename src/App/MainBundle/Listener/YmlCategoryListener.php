<?php
namespace App\MainBundle\Listener;

use App\YandexMarketBundle\Element\Category;
use App\YandexMarketBundle\Event\YmlGenerateEvent;
use App\YandexMarketBundle\Service\YmlListenerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Слушатель для генерации yml файла средствами YandexMarketBundle
 * Class YmlCategoryListener
 * @package App\MainBundle\Listener
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
	 * @return mixed|void
	 */
	public function generate(YmlGenerateEvent $event)
	{
		$categories = $this
			->entityManager
			->getRepository('AppMainBundle:Category')
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
