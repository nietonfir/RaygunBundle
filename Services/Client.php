<?php
namespace Nietonfir\RaygunBundle\Services;

use Raygun4php\RaygunClient;

class Client extends RaygunClient implements NietonfirRaygunClient
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
        $this->SendException($exception, $tags, $userCustomData, $timestamp);
    }

    public function sendRaygunError($errno, $errstr, $errfile, $errline, $tags = null, $userCustomData = null, $timestamp = null)
    {
        $tags = $this->mergeTags($tags);
        return $this->SendError($errno, $errstr, $errfile, $errline, $tags, $userCustomData, $timestamp);
    }
}
