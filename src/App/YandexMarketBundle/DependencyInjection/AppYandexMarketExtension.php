<?php

namespace App\YandexMarketBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AppYandexMarketExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.yml');
		$container->setParameter($this->getAlias() . '.filename', $config['filename']);
		$container->setParameter($this->getAlias() . '.shop.name', $config['shop']['name']);
		$container->setParameter($this->getAlias() . '.shop.company', $config['shop']['company']);
		$container->setParameter($this->getAlias() . '.shop.url', $config['shop']['url']);
		$container->setParameter($this->getAlias() . '.shop.delivery_options.cost', $config['shop']['delivery_options']['cost']);
		$container->setParameter($this->getAlias() . '.shop.delivery_options.days', $config['shop']['delivery_options']['days']);
		(empty($config['shop']['platform'])) ?: $container->setParameter($this->getAlias() . '.shop.platform', $config['shop']['platform']);
		(empty($config['shop']['version'])) ?: $container->setParameter($this->getAlias() . '.shop.version', $config['shop']['version']);
		(empty($config['shop']['agency'])) ?: $container->setParameter($this->getAlias() . '.shop.agency', $config['shop']['agency']);
		(empty($config['shop']['email'])) ?: $container->setParameter($this->getAlias() . '.shop.email', $config['shop']['email']);
		(empty($config['shop']['cpa'])) ?: $container->setParameter($this->getAlias() . '.shop.cpa', $config['shop']['cpa']);
	}
}
