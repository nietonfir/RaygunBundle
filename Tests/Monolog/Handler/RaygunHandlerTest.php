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

    public function httpExceptionsProvider()
    {
        return array(
            array(true, $this->getMockBuilder('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')),
            array(true, $this->getMockBuilder('Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException')
                        ->setConstructorArgs(array('test'))),
            array(false, $this->getMockBuilder('\Exception'))
        );
    }

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Nietonfir\RaygunBundle\Services\RaygunClient')
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
            ->method('sendRaygunError')
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
            ->method('sendRaygunException')
            ->with($exceptionMock);

        $handler = new RaygunHandler($this->client);
        $handler->handle($record);
    }

    /**
     * @dataProvider httpExceptionsProvider
     */
    public function testIgnoreHttpExceptions($isHttpException, $mockBuilder)
    {
        $exceptionMock = $mockBuilder->getMock();

        $record = $this->getRecord(Logger::CRITICAL, 'test', array('exception' => $exceptionMock));

        if (true == $isHttpException) {
            $this->client->expects($this->never())->method('sendRaygunException');
        } else {
            $this->client->expects($this->once())->method('sendRaygunException');
        }

        $handler = new RaygunHandler($this->client);
        $handler->setIgnoreHttpExceptions(true);
        $handler->handle($record);
    }
}
