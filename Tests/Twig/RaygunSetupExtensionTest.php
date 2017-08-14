<?php

/*
 * This file is part of the Raygunbundle package.
 *
 * (c) nietonfir <nietonfir@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\RaygunBundle\Tests\Twig;

use Nietonfir\RaygunBundle\Twig\RaygunSetupExtension;

class RaygunSetupExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $reflection = new \ReflectionProperty('Nietonfir\RaygunBundle\Twig\RaygunSetupExtension', 'apiKey');
        $reflection->setAccessible(true);

        $apiKey = '1234567';
        $extension = new RaygunSetupExtension($apiKey);

        $this->assertInstanceof('\Twig_Extension', $extension);
        $this->assertInstanceof('\Twig_Extension_GlobalsInterface', $extension);
        $this->assertEquals('nietonfir_raygun_setup', $extension->getName());
        $this->assertEquals($apiKey, $reflection->getValue($extension));
    }

    public function testGlobals()
    {
        $apiKey = '1234567';
        $extension = new RaygunSetupExtension($apiKey);

        $this->assertEquals(array('raygun_api_key' => $apiKey), $extension->getGlobals());
    }

    public function testGlobalsVersion()
    {
        $apiKey = '1234567';
        $extension = new RaygunSetupExtension($apiKey);
        $extension->setVersion('1.0.0');
        $this->assertEquals(
            array(
                'raygun_api_key' => $apiKey,
                'raygun_app_version' => '1.0.0'
            ),
            $extension->getGlobals());
    }

    public function testGlobalsDefaultTags()
    {
        $apiKey = '1234567';
        $extension = new RaygunSetupExtension($apiKey);
        $extension->setDefaultTags(array('a', 'b', 'c'));
        $this->assertEquals(
            array(
                'raygun_api_key' => $apiKey,
                'raygun_default_tags' => array('a', 'b', 'c')),
            $extension->getGlobals());
    }

    public function testAddExtension()
    {
        $apiKey = '1234567';

        $twig = new \Twig_Environment($this->getMock('Twig_LoaderInterface'));
        $twig->addExtension(new RaygunSetupExtension($apiKey));
        $globals = $twig->getGlobals();

        $this->assertArrayHasKey('raygun_api_key', $globals);
        $this->assertEquals($apiKey, $globals['raygun_api_key']);
    }
}
