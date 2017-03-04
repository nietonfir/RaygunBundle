<?php

/*
 * This file is part of the Raygunbundle package.
 *
 * (c) nietonfir <nietonfir@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\RaygunBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Nietonfir\RaygunBundle\DependencyInjection\NietonfirRaygunExtension;
use Symfony\Component\Yaml\Parser;

class NietonfirRaygunExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    protected $configuration;

    public function testDefaults()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new NietonfirRaygunExtension();
        $config = $this->getEmptyConfig();
        $loader->load(array($config), $this->configuration);

        $this->assertAlias('nietonfir_raygun.client', 'raygun.client');
        $this->assertAlias('nietonfir_raygun.monolog_handler', 'raygun.handler');
        $this->assertParameter('1234567', 'nietonfir_raygun.api_key');
        $this->assertParameter(true, 'nietonfir_raygun.async');
        $this->assertParameter(false, 'nietonfir_raygun.debug_mode');
        $this->assertParameter(false, 'nietonfir_raygun.disable_user_tracking');
        $this->assertParameter(null, 'nietonfir_raygun.app_version');
        $this->assertParameter(array(), 'nietonfir_raygun.tags');
        $this->assertHasDefinition('nietonfir_raygun.monolog_handler');
        $this->assertHasDefinition('nietonfir_raygun.twig_extension');
        $this->assertDoesNotHaveCall('nietonfir_raygun.monolog_handler', 'setIgnoreHttpExceptions');
    }

    public function testCustomSettings()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new NietonfirRaygunExtension();
        $config = $this->getFullConfig();
        $loader->load(array($config), $this->configuration);
        $client = $this->configuration->get('nietonfir_raygun.client');

        $this->assertAlias('nietonfir_raygun.client', 'raygun.client');
        $this->assertAlias('nietonfir_raygun.monolog_handler', 'raygun.handler');
        $this->assertParameter('987655', 'nietonfir_raygun.api_key');
        $this->assertParameter(false, 'nietonfir_raygun.async');
        $this->assertParameter(true, 'nietonfir_raygun.debug_mode');
        $this->assertParameter(true, 'nietonfir_raygun.disable_user_tracking');
        $this->assertHasDefinition('nietonfir_raygun.monolog_handler');
        $this->assertHasDefinition('nietonfir_raygun.twig_extension');
        $this->assertParameter('1.0.0', 'nietonfir_raygun.app_version');
        $this->assertParameter(array('a', 'b', 'c'), 'nietonfir_raygun.tags');
        $this->assertHasCall('nietonfir_raygun.monolog_handler', 'setIgnoreHttpExceptions');
        $this->assertHasCall('nietonfir_raygun.client', 'setVersion');
        $this->assertHasCall('nietonfir_raygun.client', 'setDefaultTags');
        $this->assertEquals('1.0.0', $this->getObjAttribute($client, 'version'));
        $this->assertEquals(array('a', 'b', 'c'), $this->getObjAttribute($client, 'defaultTags'));
    }

    /**
     * getEmptyConfig
     *
     * @return array
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF
api_key: 1234567
EOF;
        $parser = new Parser();
        return $parser->parse($yaml);
    }

    /**
     * getEmptyConfig
     *
     * @return array
     */
    protected function getFullConfig()
    {
        $yaml = <<<EOF
api_key: 987655
async: false
debug_mode: true
track_users: false
ignore_http_exceptions: true
app_version: 1.0.0
tags: ['a', 'b', 'c']
EOF;
        $parser = new Parser();
        return $parser->parse($yaml);
    }

    /**
     * @param string $value
     * @param string $key
     */
    private function assertAlias($value, $key)
    {
        $this->assertEquals($value, (string) $this->configuration->getAlias($key), sprintf('%s alias is correct', $key));
    }

    /**
     * @param mixed  $value
     * @param string $key
     */
    private function assertParameter($value, $key)
    {
        $this->assertEquals($value, $this->configuration->getParameter($key), sprintf('%s parameter is correct', $key));
    }

    /**
     * @param string $id
     */
    private function assertHasDefinition($id)
    {
        $this->assertTrue(($this->configuration->hasDefinition($id) ?: $this->configuration->hasAlias($id)));
    }

    private function assertHasCall($id, $method)
    {
        $definition = $this->configuration->getDefinition($id);
        $this->assertTrue($definition->hasMethodCall($method));
    }

    private function assertDoesNotHaveCall($id, $method)
    {
        $definition = $this->configuration->getDefinition($id);
        $this->assertFalse($definition->hasMethodCall($method));
    }

    protected function tearDown()
    {
        unset($this->configuration);
    }

    protected function & getObjAttribute($object, $property)
    {
        $value = & \Closure::bind(function & () use ($property) {
            return $this->$property;
        }, $object, $object)->__invoke();

        return $value;
    }
}
