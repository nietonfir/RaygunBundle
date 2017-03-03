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
        $this->assertArrayHasKey('track_users', $config);
        $this->assertArrayHasKey('ignore_http_exceptions', $config);
        $this->assertArrayHasKey('app_version', $config);
        $this->assertArrayHasKey('tags', $config);
        $this->assertEquals($key, $config['api_key']);
        $this->assertTrue($config['async']);
        $this->assertFalse($config['debug_mode']);
        $this->assertTrue($config['track_users']);
        $this->assertNull($config['app_version']);
        $this->assertFalse($config['ignore_http_exceptions']);
        $this->assertEmpty($config['tags']);
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

    public function testDisableUserTracking()
    {
        $key = '1234567';

        $configs = array(
            array(
                'api_key' => $key,
                'track_users' => false
            )
        );
        $config = $this->process($configs);

        $this->assertArrayHasKey('track_users', $config);
        $this->assertFalse($config['track_users']);
    }

    public function testIgnoreHttpExceptionsModeSet()
    {
        $key = '1234567';

        $configs = array(
            array(
                'api_key' => $key,
                'ignore_http_exceptions' => true
            )
        );
        $config = $this->process($configs);

        $this->assertArrayHasKey('ignore_http_exceptions', $config);
        $this->assertTrue($config['ignore_http_exceptions']);
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

    public function testValidVersionSet()
    {
        $key = '1234567';

        $configs = array(
            array(
                'api_key' => $key,
                'app_version' => '1.0.0'
            )
        );
        $config = $this->process($configs);

        $this->assertArrayHasKey('app_version', $config);
        $this->assertEquals('1.0.0', $config['app_version']);
    }

    public function testValidDefaultTagsSet()
    {
        $key = '1234567';

        $configs = array(
            array(
                'api_key' => $key,
                'tags' => array('a', 'b', 'c')
            )
        );
        $config = $this->process($configs);

        $this->assertArrayHasKey('tags', $config);
        $this->assertEquals(array('a', 'b', 'c'), $config['tags']);
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
