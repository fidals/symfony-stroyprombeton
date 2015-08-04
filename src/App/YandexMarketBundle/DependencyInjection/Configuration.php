<?php

namespace App\YandexMarketBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app_yandex_market');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
		$rootNode
			->children()
				->scalarNode('filename')->defaultValue('market')->end()
				->arrayNode('shop')
					->children()
						->scalarNode('name')->isRequired()->end()
						->scalarNode('company')->isRequired()->end()
						->scalarNode('url')->isRequired()->end()
						->scalarNode('platform')->end()
						->scalarNode('version')->end()
						->scalarNode('agency')->end()
						->scalarNode('email')->end()
						->integerNode('cpa')
							->min(0)
							->max(1)
						->end()
						->integerNode('local_delivery_cost')->isRequired()->end()
					->end()
			    ->end()
			->end()
		;

        return $treeBuilder;
    }
}
