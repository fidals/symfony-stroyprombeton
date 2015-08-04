<?php
namespace App\YandexMarketBundle\DependencyInjection\Compiler;

use App\YandexMarketBundle\Event\YmlGenerateEvent;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Собирает все классы с тегом yandex_market.yml.listener и в случае события вызывает у них generate метод
 * Class AddYmlListenersPass
 * @package App\YandexMarketBundle\DependencyInjection\Compiler
 */
class AddYmlListenersPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
	{
		if (!$container->hasDefinition('event_dispatcher') && !$container->hasAlias('event_dispatcher')) {
			return;
		}
		$definition = $container->findDefinition('event_dispatcher');
		foreach ($container->findTaggedServiceIds('yandex_market.yml.listener') as $id => $tags) {
			$class = $container->getDefinition($id)->getClass();
			$refClass = new \ReflectionClass($class);
			$interface = 'App\YandexMarketBundle\Service\YmlListenerInterface';
			if (!$refClass->implementsInterface($interface)) {
				throw new \InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, $interface));
			}
			$definition->addMethodCall(
				'addListenerService',
				array(YmlGenerateEvent::ON_YML_GENERATE, array($id, 'generate'))
			);
		}
	}
}