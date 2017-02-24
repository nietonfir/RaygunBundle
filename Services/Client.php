<?php
namespace Nietonfir\RaygunBundle\Services;

use Raygun4php\RaygunClient;

class Client extends RaygunClient
{
    private $defaultTags = null;

    public function setDefaultTags(array $tags)
    {
        $this->defaultTags = $tags;
    }

    public function setVersion($version)
    {
        parent::SetVersion($version);
    }

    public function SendError($errno, $errstr, $errfile, $errline, $tags = null, $userCustomData = null, $timestamp = null)
    {
        if (is_array($this->defaultTags)) {
            if (is_array($tags)) {
                $tags = array_merge($this->defaultTags, $tags);
            } else {
                $tags = $this->defaultTags;
            }
        }

        return parent::SendError($errno, $errstr, $errfile, $errline, $tags, $userCustomData, $timestamp);
    }
}