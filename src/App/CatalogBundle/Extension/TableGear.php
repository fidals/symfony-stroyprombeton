<?php

namespace App\CatalogBundle\Extension;

use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TableGear
{
	const INC_PHP_PATH = '/TableGear/tablegear.inc.php';
	const LIB_PATH = '/TableGear/TableGear1.6.1.php';

	public $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	public function getContent()
	{
		$args = array(
			'container'         => $this->container,
			'productRepository' => $this->container->get('doctrine')->getRepository('AppCatalogBundle:Product'),
			'libPath'           => dirname(__FILE__) . self::LIB_PATH
		);
		return self::call($args);
	}

	private function call(array $args) {
		extract($args);
		$filePath = dirname(__FILE__) . self::INC_PHP_PATH;
		if(file_exists($filePath)) {
			return require $filePath;
		} else {
			throw new FileLoaderLoadException($filePath);
		}
	}
}