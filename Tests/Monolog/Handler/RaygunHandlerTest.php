<?php

/*
 * This file is part of the Raygunbundle package.
 *
 * (c) nietonfir <nietonfir@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\RaygunBundle\Tests\Monolog\Handler;

use Monolog\TestCase;
use Monolog\Logger;
use Nietonfir\RaygunBundle\Monolog\Handler\RaygunHandler;

class RaygunHandlerTest extends TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Raygun4php\RaygunClient')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testConstruct()
    {
        $reflection = new \ReflectionProperty('Nietonfir\RaygunBundle\Monolog\Handler\RaygunHandler', 'client');
        $reflection->setAccessible(true);

        $handler = new RaygunHandler($this->client);
        $this->assertEquals($this->client, $reflection->getValue($handler));
    }

    public function testHandleError()
    {
        $record = $this->getRecord(Logger::CRITICAL, 'test', array('file' => __FILE__, 'line' => 42));

        $this->client->expects($this->once())
            ->method('sendError')
            ->with(
                $this->equalTo(Logger::CRITICAL),
                $this->equalTo('test'),
                $this->equalTo(__FILE__),
                $this->equalTo(42)
            );

        $handler = new RaygunHandler($this->client);
        $handler->handle($record);
    }

    public function testHandleException()
    {
        $exceptionMock = $this->getMock('Exception');

        $record = $this->getRecord(Logger::CRITICAL, 'test', array('exception' => $exceptionMock));

        $this->client->expects($this->once())
            ->method('sendException')
            ->with($exceptionMock);

        $handler = new RaygunHandler($this->client);
        $handler->handle($record);
    }

    public function testIgnore404()
    {
        $exceptionMock = $this->getMock('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');

        $record = $this->getRecord(Logger::CRITICAL, 'test', array('exception' => $exceptionMock));

        $this->client->expects($this->never())
            ->method('sendException');

        $handler = new RaygunHandler($this->client);
        $handler->setIgnore404(true);
        $handler->handle($record);
    }
}
