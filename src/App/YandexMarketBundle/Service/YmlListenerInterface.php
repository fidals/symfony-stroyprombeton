<?php
namespace App\YandexMarketBundle\Service;

use App\YandexMarketBundle\Event\YmlGenerateEvent;

/**
 * Интерфейс слушателя события генерации yml файла для Yandex Market
 * Class YmlListenerInterface
 * @package App\YandexMarketBundle\Service
 */
interface YmlListenerInterface
{
	/**
	 * Метод, который будет вызываться по событию генерации yml файла
	 * @param YmlGenerateEvent $event
	 * @return mixed
	 */
	public function generate(YmlGenerateEvent $event);
}