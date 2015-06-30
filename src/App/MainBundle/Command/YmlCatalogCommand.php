<?php
namespace App\MainBundle\Command;

use App\CatalogBundle\Command\SitemapCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class YmlCatalogCommand extends ContainerAwareCommand
{
	const FILE_YML = '/var/www/stroyprombeton/web/YML.xml';
	const URL_INDEX = 'http://www.stroyprombeton.ru';

	// you can modify this $repositories array for include some entities in sitemap
	// each entity MUST consist sitemapData() method
	public $repositories = array(
		'AppCatalogBundle:Category',
		'AppCatalogBundle:Product'
	);

	protected function configure()
	{
		$this->setName('yml:generate')
			->setDescription('Generate sitemap');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$catRp = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Category');

		$hierarchyOptions = array(
			'childSort' => array(
				'field' => 'id',
				'dir' => 'asc'
			)
		);

		$rootNodes = $catRp->getRootNodes();
		foreach ($rootNodes as $rootNode) {
			$treeNode = $catRp->buildTreeArray($catRp->getNodesHierarchy($rootNode, false, $hierarchyOptions, true));
			$tree[] = $treeNode[0];
		}

		$products = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Product')->findAll();
		foreach ($products as &$product) {
			$productSectionId = $product->getSectionId();
			if (!empty($productSectionId)) {
				$path = $catRp->getPath($catRp->find($product->getSectionId()));
				if (!empty($path[0]) && !empty(SitemapCommand::$baseCats[$path[0]->getId()])) {
					$product->catUrl = SitemapCommand::$baseCats[$path[0]->getId()];
				} else {
					$product->catUrl = SitemapCommand::$baseCats[456];
				}
			}
		}

		$yml = $this->getContainer()->get('templating')->render('AppMainBundle:Sitemap:yml.xml.twig', array(
			'urlIndex' => self::URL_INDEX,
			'tree' => $tree,
			'products' => $products
		));

		if (file_put_contents(self::FILE_YML, $yml)) {
			die('sitemap successfully generated');
		} else {
			die('sorry, server error occured');
		}
	}
}