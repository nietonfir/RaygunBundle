<?php

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
        $this->assertEquals($key, $config['api_key']);
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
