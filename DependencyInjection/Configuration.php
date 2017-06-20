<?php

/*
 * This file is part of the Raygunbundle package.
 *
 * (c) nietonfir <nietonfir@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\RaygunBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nietonfir_raygun');

        $rootNode
            ->children()
            ->scalarNode('api_key')
            ->isRequired()
            ->cannotBeEmpty()
            ->end() // api_key
            ->booleanNode('async')
            ->defaultTrue()
            ->end() // async
            ->booleanNode('track_users')
            ->defaultTrue()
            ->end() // track_users
            ->booleanNode('debug_mode')
            ->defaultFalse()
            ->end() // debug_mode
            ->arrayNode('tags')
            ->prototype('scalar')
            ->end()
            ->end()
            ->booleanNode('ignore_http_exceptions')
            ->defaultFalse()
            ->end() // ignore_http_exceptions
            ->scalarNode('app_version')
            ->defaultNull()
            ->end()
            ->end()
            ->validate()
            ->ifTrue(function($v){return $v['debug_mode'];})
            ->then(function($v){$v['async'] = false;return $v;})
            ->end();

        return $treeBuilder;
    }
}
