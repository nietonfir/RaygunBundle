<?php
namespace Nietonfir\RaygunBundle\Monolog\Handler;

use Raygun4php\RaygunClient;

class Client extends RaygunClient
{
    private $defaultTags = null;

    public function setDefaultTags(array $tags)
    {
        $this->defaultTags = $tags;
        //this is a commit tetst
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