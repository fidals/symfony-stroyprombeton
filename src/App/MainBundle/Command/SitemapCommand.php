<?php
namespace App\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class SitemapCommand extends ContainerAwareCommand
{
	const FILE_SITEMAP = '/../web/sitemap.xml';
	const URL_INDEX = 'http://www.stroyprombeton.ru';

	// you can modify this $repositories array for include some entities in sitemap
	// each entity MUST consist sitemapData() method
	public $repositories = array(
		'AppMainBundle:StaticPage',
		'AppMainBundle:Category',
		'AppMainBundle:Product'
	);

	// ugly urls
	public static $baseCats = array(
		456 => 'prom-stroy',
		457 => 'dor-stroy',
		458 => 'ingener-stroy',
		459 => 'energy-stroy',
		460 => 'blag-territory',
		461 => 'neftegaz-stroy'
	);

	protected function configure()
	{
		$this->setName('sitemap:generate')
			->setDescription('Generate sitemap');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$entityList = $this->generate($this->repositories);
		$sitemap = $this->getContainer()->get('templating')->render('AppMainBundle:Sitemap:sitemap.xml.twig', array(
			'entityList' => $entityList,
			'urlIndex' => self::URL_INDEX
		));
		if (file_put_contents($this->getContainer()->get('kernel')->getRootDir() . self::FILE_SITEMAP, $sitemap)) {
			die('sitemap successfully generated');
		} else {
			die('sorry, server error occured');
		}
	}

	public function generate($entityRepositories)
	{
		try {
			$entityList = array();
			$catRp = $this->getContainer()->get('doctrine')->getRepository('AppMainBundle:Category');
			foreach ($entityRepositories as $repositoryName) {
				$rep = $this->getContainer()->get('doctrine')->getRepository($repositoryName);
				$entities = $rep->findAll();
				if ($repositoryName == 'AppMainBundle:Category') {
					foreach ($entities as $entity) {
						$path = $catRp->getPath($entity);
						if (!empty($path[0]) && !empty(self::$baseCats[$path[0]->getId()])) {
							$catUrl = self::$baseCats[$path[0]->getId()];
						} else {
							$catUrl = self::$baseCats[456];
						}
						$entityData = $entity->getSitemapData();
						$entityData['locData']['parameters']['catUrl'] = $catUrl;
						$entityList[] = $this->assemble($entityData);
					}
				} elseif ($repositoryName == 'AppMainBundle:Product') {
					foreach ($entities as $entity) {
						$sectionId = $entity->getSectionId();
						if (!empty($sectionId)) {
							$path = $catRp->getPath($catRp->find($sectionId));
							if (!empty($path[0]) && !empty(self::$baseCats[$path[0]->getId()])) {
								$catUrl = self::$baseCats[$path[0]->getId()];
							} else {
								$catUrl = self::$baseCats[456];
							}
						} else {
							$catUrl = self::$baseCats[456];
						}
						$entityData = $entity->getSitemapData();
						$entityData['locData']['parameters']['catUrl'] = $catUrl;
						$entityList[] = $this->assemble($entityData);
					}
				} else {
					foreach ($entities as $entity) {
						$entityList[] = $this->assemble($entity->getSitemapData());
					}
				}
			}
			return $entityList;
		} catch (\Exception $e) {
			$mailer = $this->getContainer()->get('mailer');
			$message = \Swift_Message::newInstance()
				->setSubject('Ошибка выполнения команды на сайте ' . $this->getContainer()->getParameter('shop_mail_address'))
				->addTo('support@fidals.ru')
				->setFrom('error@stroyprombeton.ru')
				->setContentType("text/html")
				->setBody('при генерации sitemap выброшено исключение: ' . $e->getMessage(), "\n");
			$mailer->send($message);
			$spool = $mailer->getTransport()->getSpool();
			$transport = $this->getContainer()->get('swiftmailer.transport.real');
			$spool->flushQueue($transport);
		}

	}

	public function assemble($entityData)
	{
		//Постфикс. Нужен для генерации url. У продуктов, например, подставляем id с вопросиками
		$urlPostfix = '';
		if ($entityData['entityType'] == 'product')
			$urlPostfix = '?section=' . $entityData['section'] . '&gbi=' . $entityData['gbi'];
		elseif ($entityData['entityType'] == 'category')
			$urlPostfix = '?section=' . $entityData['section']; else {
			//TODO: Бросаем Exception. Скорее всего есть симфониевый
		}
		$urlPostfix = str_replace("&amp;", "&", $urlPostfix);

		return array(
			'loc' => (empty($entityData['loc'])) ?
				$this->getContainer()->get('router')
					->generate($entityData['locData']['route'], $entityData['locData']['parameters'], false) . $urlPostfix
				: $entityData['loc'],
			'lastmod' => (empty($entityData['lastmod'])) ? date("Y-m-d H:i:s") : $entityData['lastmod'],
			'changefreq' => (empty($entityData['changefreq'])) ? 'weekly' : $entityData['changefreq'],
			'priority' => (empty($entityData['priority'])) ? '1' : $entityData['priority'],
			'name' => (empty($entityData['name'])) ? '1' : $entityData['name'],
		);
	}

	public function assemble1($entityData)
	{
		return array(
			'loc' => (empty($entityData['loc'])) ? $this->getContainer()->get('router')->generate($entityData['locData']['route'], $entityData['locData']['parameters'], false) : $entityData['loc'],
			'lastmod' => (empty($entityData['lastmod'])) ? date("Y-m-d H:i:s") : $entityData['lastmod'],
			'changefreq' => (empty($entityData['changefreq'])) ? 'weekly' : $entityData['changefreq'],
			'priority' => (empty($entityData['priority'])) ? '1' : $entityData['priority'],
		);
	}

}
