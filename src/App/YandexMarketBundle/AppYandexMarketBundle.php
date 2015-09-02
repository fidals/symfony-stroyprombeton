<?php
namespace App\YandexMarketBundle;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\YandexMarketBundle\DependencyInjection\Compiler\AddYmlListenersPass;

class AppYandexMarketBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
		$container->addCompilerPass(new AddYmlListenersPass(), PassConfig::TYPE_OPTIMIZE);
	}
}