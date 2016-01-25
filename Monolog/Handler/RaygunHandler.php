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

class RaygunHandler extends AbstractProcessingHandler
{
    protected $client;

    /**
     * @param RaygunClient $client The Raygun.io client responsible for sending errors/exceptions to Raygun
     * @param int          $level  The minimum logging level at which this handler will be triggered
     * @param Boolean      $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(RaygunClient $client, $level = Logger::ERROR, $bubble = true)
    {
        $this->client = $client;

        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $ctx = $record['context'];
        $exception = isset($ctx['exception']) ? $ctx['exception'] : false;

        if ($exception) {
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
