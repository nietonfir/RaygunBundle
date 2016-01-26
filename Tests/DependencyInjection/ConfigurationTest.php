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

use Nietonfir\RaygunBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessDefaultConfig()
    {
        $key = '1234567';

        $configs = array(
            array(
                'api_key' => $key
            )
        );
        $config = $this->process($configs);

        $this->assertArrayHasKey('api_key', $config);
        $this->assertArrayHasKey('async', $config);
        $this->assertArrayHasKey('debug_mode', $config);
        $this->assertEquals($key, $config['api_key']);
        $this->assertTrue($config['async']);
        $this->assertFalse($config['debug_mode']);
    }

    public function testDebugModeSet()
    {
        $key = '1234567';

        $configs = array(
            array(
                'api_key' => $key,
                'debug_mode' => true
            )
        );
        $config = $this->process($configs);

        $this->assertArrayHasKey('async', $config);
        $this->assertArrayHasKey('debug_mode', $config);
        $this->assertFalse($config['async']);
        $this->assertTrue($config['debug_mode']);
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testInvalidConfig()
    {
        $configs = array(
            array(
                'api_key' => null,
                'async' => 'blub',
                'debug_mode' => 'blub'
            )
        );
        $config = $this->process($configs);
    }

    /**
     * Processes an array of configurations and returns a compiled version similar to
     * @see \Symfony\Component\HttpKernel\DependencyInjection\Extension
     *
     * @param array $configs An array of raw configurations
     * @return array A normalized array
     */
    protected function process($configs)
    {
        $processor = new Processor();
        return $processor->processConfiguration(new Configuration(), $configs);
    }
}
