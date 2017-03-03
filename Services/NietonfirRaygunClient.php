<?php
namespace Nietonfir\RaygunBundle\Services;

interface NietonfirRaygunClient
{
    function setDefaultTags(array $tags);
    function setVersion($version);

    function sendRaygunException($exception, $tags = null, $userCustomData = null, $timestamp = null);
    function sendRaygunError($errno, $errstr, $errfile, $errline, $tags = null, $userCustomData = null, $timestamp = null);
}
