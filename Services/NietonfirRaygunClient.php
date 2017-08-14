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

interface NietonfirRaygunClient
{
    /**
     * It sets the default tags that will be transmit to Raygun.io API, along with the ones
     * bound to the exception or error.
     * @param array $tags An empty array is accepted
     * @return void
     */
    function setDefaultTags(array $tags);

    /**
     * It sets the version that will be sent to Raygun.
     * @param mixed $version
     * @return void
     */
    function setVersion($version);

    /**
     * Transmits an exception to the Raygun.io API
     * @param \Exception $exception An exception object to transmit
     * @param array|null $tags An optional array of string tags used to provide metadata for the message,
     * along with the default optional ones, if any set.
     * @param array|null $userCustomData An optional associative array that can be used to place custom key-value
     * data in the message payload
     * @param int|null $timestamp Current Unix timestamp in the local timezone, used to set when an exception occurred.
     * @return int The HTTP status code of the result when transmitting the message to Raygun.io
     */
    function sendRaygunException($exception, $tags = null, $userCustomData = null, $timestamp = null);

    /**
     * Transmits an error to the Raygun.io API
     * @param int $errorno The error number
     * @param string $errstr The error string
     * @param string $errfile The file the error occurred in
     * @param int $errline The line the error occurred on
     * @param array|null $tags An optional array of string tags used to provide metadata for the message,
     * along with the default optional ones, if any set.
     * @param array|null $userCustomData An optional associative array that can be used to place custom key-value
     * @param int $timestamp Current Unix timestamp in the local timezone, used to set when an error occurred.
     * data in the message payload
     * @return int The HTTP status code of the result when transmitting the message to Raygun.io
     */
    function sendRaygunError($errno, $errstr, $errfile, $errline, $tags = null, $userCustomData = null, $timestamp = null);
}
