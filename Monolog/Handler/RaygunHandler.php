<?php

/*
 * This file is part of the Raygunbundle package.
 *
 * (c) nietonfir <nietonfir@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\RaygunBundle\Monolog\Handler;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Raygun4php\RaygunClient;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RaygunHandler extends AbstractProcessingHandler
{
    protected $client;

    /**
     * @var bool
     */
    private $ignoreHttpExceptions = false;

    /**
     * @param RaygunClient $client The Raygun.io client responsible for sending errors/exceptions to Raygun
     * @param int          $level  The minimum logging level at which this handler will be triggered
     * @param Boolean      $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(RaygunClient $client, $level = Logger::ERROR, $bubble = true, $version = null)
    {
        $this->client = $client;

        parent::__construct($level, $bubble);
    }

    /**
     * @param bool $ignoreHttpExceptions
     */
    public function setIgnoreHttpExceptions($ignoreHttpExceptions)
    {
        $this->ignoreHttpExceptions = (bool) $ignoreHttpExceptions;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $ctx = $record['context'];
        $exception = isset($ctx['exception']) ? $ctx['exception'] : false;

        if ($exception) {
            if ($this->ignoreHttpExceptions && ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)) {
                return;
            }

            $this->client->sendException($exception);
        } else {
            $this->client->sendError($record['level'], $record['message'], $ctx['file'], $ctx['line']);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new NormalizerFormatter();
    }
}
