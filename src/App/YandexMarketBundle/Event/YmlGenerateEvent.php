<?php
namespace App\YandexMarketBundle\Event;

use App\YandexMarketBundle\Service\YmlGenerator;
use Symfony\Component\EventDispatcher\Event;

/**
 * Объект события, передается всем слушателям по порядку
 * Class YmlGenerateEvent
 * @package App\YandexMarketBundle\Event
 */
class YmlGenerateEvent extends Event
{
	/**
	 * Название события
	 */
	const ON_YML_GENERATE = 'yandex_market.yml.generate';

	/**
	 * Необходим для добавления сущностей offer, category и других в yml файл
	 * @var \App\YandexMarketBundle\Service\YmlGenerator
	 */
	private $generator;

	/**
	 * @param YmlGenerator $generator
	 */
	public function __construct(YmlGenerator $generator)
	{
		$this->generator = $generator;
	}

	/**
	 * @return YmlGenerator
	 */
	public function getGenerator()
	{
		return $this->generator;
	}
}
