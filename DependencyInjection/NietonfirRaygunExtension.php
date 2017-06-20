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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class NietonfirRaygunExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // set the Raygun API key
        $container->setParameter('nietonfir_raygun.api_key', $config['api_key']);
        $container->setParameter('nietonfir_raygun.async', $config['async']);
        $container->setParameter('nietonfir_raygun.debug_mode', $config['debug_mode']);
        $container->setParameter('nietonfir_raygun.disable_user_tracking', !$config['track_users']);
        $container->setParameter('nietonfir_raygun.app_version', $config['app_version']);
        $container->setParameter('nietonfir_raygun.tags', $config['tags']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $this->configureHandler($config, $container);
    }

    protected function configureHandler(array $config, ContainerBuilder $container)
    {
        if ($config['ignore_http_exceptions']) {
            $handlerDefinition = $container->getDefinition('nietonfir_raygun.monolog_handler');
            $handlerDefinition->addMethodCall('setIgnoreHttpExceptions', array(true));
        }
    }
}
