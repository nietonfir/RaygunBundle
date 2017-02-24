<?php
namespace Nietonfir\RaygunBundle\Services;

use Raygun4php\RaygunClient;

class Client extends RaygunClient
{
    private $defaultTags = null;
    protected $version;

    protected function mergeTags ($tags)
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

    public function SendException($exception, $tags = null, $userCustomData = null, $timestamp = null)
    {
        $tags = $this->mergeTags($tags);
        parent::SendException($exception, $tags, $userCustomDatam, $timestamp);
    }

    public function SendError($errno, $errstr, $errfile, $errline, $tags = null, $userCustomData = null, $timestamp = null)
    {
        $tags = $this->mergeTags($tags);
        return parent::SendError($errno, $errstr, $errfile, $errline, $tags, $userCustomData, $timestamp);
    }
}