<?php

/*
 * This file is part of the Raygunbundle package.
 *
 * (c) nietonfir <nietonfir@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\RaygunBundle\Services;

use Raygun4php\RaygunClient as OriginalRaygunClient;

class RaygunClient extends OriginalRaygunClient implements NietonfirRaygunClient
{
    private $defaultTags = null;

    protected function mergeTags($tags)
    {
        if (is_array($this->defaultTags)) {
            if (is_array($tags)) {
                $tags = array_merge($this->defaultTags, $tags);
            } else {
                $tags = $this->defaultTags;
            }
        }

        return $tags;
    }

    public function setDefaultTags(array $tags)
    {
        if (0 == count($tags)) {
            $this->defaultTags = null;
        }

        $this->defaultTags = $tags;
    }

    public function setVersion($version)
    {
        parent::SetVersion($version);
    }

    public function sendRaygunException($exception, $tags = null, $userCustomData = null, $timestamp = null)
    {
        $tags = $this->mergeTags($tags);
        return $this->SendException($exception, $tags, $userCustomData, $timestamp);
    }

    public function sendRaygunError($errno, $errstr, $errfile, $errline, $tags = null, $userCustomData = null, $timestamp = null)
    {
        $tags = $this->mergeTags($tags);
        return $this->SendError($errno, $errstr, $errfile, $errline, $tags, $userCustomData, $timestamp);
    }
}
