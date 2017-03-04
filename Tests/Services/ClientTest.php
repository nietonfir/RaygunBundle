<?php

/*
 * This file is part of the Raygunbundle package.
 *
 * (c) nietonfir <nietonfir@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\RaygunBundle\Tests\Services;

use Monolog\TestCase;

class ClientTest extends TestCase
{
    protected $raygunClient;
    protected $myClient;


    public function setUp()
    {
        $this->myClient = $this->getMockBuilder('Nietonfir\RaygunBundle\Services\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('SendError'))
            ->getMock();
    }

    public function testSendTagsError()
    {
        $this->myClient->setDefaultTags(array('a', 'b', 'c'));
        $this->myClient->expects($this->once())
            ->method('SendError')
            ->with(1, 'str', 'file', 12, array('a', 'b', 'c', 'd'), null, null);
        $this->myClient->sendRaygunError(1, 'str', 'file', 12, array('d'));
    }

    public function testSendTagsException()
    {
        $this->myClient->setDefaultTags(array('a', 'b', 'c'));
        $this->myClient->expects($this->once())
            ->method('SendException')
            ->with(1, 'str', 'file', 12, array('a', 'b', 'c', 'd'), null, null);
        $exceptionMock = $this->getMock('Exception');
        $this->myClient->sendRaygunException($exceptionMock, 'str', 'file', 12, array('d'));
    }
}
