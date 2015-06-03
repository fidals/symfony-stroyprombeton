<?php

namespace App\CatalogBundle\Extension;

use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Обертка над расширением плагина редактирования таблиц
 * См http://andrewplummer.com/code/tablegear/
 * Class TableGear
 * @package App\CatalogBundle\Extension
 */
class TableGear
{
	/**
	 * Путь к конфигу и одновременно файлу с разметкой, который использует TableGear
	 * Путь относительно этого файла
	 * Взят с ModX
	 */
	const INC_PHP_PATH = '/TableGear/tablegear.inc.php';

	/**
	 * Путь к библиотеке TableGear
	 * Путь относительно этого файла
	 */
	const LIB_PATH = '/TableGear/TableGear1.6.1.php';

	/**
	 * Храним ссылку на контейнер для передачи параметров прямо из parameters.yml в tablegear.inc.php
	 * @var \Symfony\Component\DependencyInjection\ContainerInterface
	 */
	public $container;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	/**
	 * Возвращает отрисованный контент с табличкой от TableGear
	 * @return mixed
	 */
	public function getContent()
	{
		$args = array(
			'container'         => $this->container,
			'productRepository' => $this->container->get('doctrine')->getRepository('AppCatalogBundle:Product'),
			'libPath'           => dirname(__FILE__) . self::LIB_PATH
		);
		return self::call($args);
	}

	/**
	 * Выполняет require файла, делает доступными в нем переданные аргументы
	 * Если файл не существует - выбрасывает исключение FileLoaderLoadException
	 * @param array $args ассоциативный массив аргументов
	 * @return mixed
	 * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
	 */
	private static function call(array $args) {
		extract($args);
		$filePath = dirname(__FILE__) . self::INC_PHP_PATH;
		if(file_exists($filePath)) {
			return require $filePath;
		} else {
			throw new FileLoaderLoadException($filePath);
		}
	}
}