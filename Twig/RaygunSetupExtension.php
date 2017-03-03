<?php

/*
 * This file is part of the Raygunbundle package.
 *
 * (c) nietonfir <nietonfir@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\RaygunBundle\Twig;

class RaygunSetupExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $apiKey;
    private $version;
    private $defaultTags;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function setDefaultTags(array $tags)
    {
        if (0 == count($tags)) {
            $this->defaultTags = null;
        }

        $this->defaultTags = $tags;
    }

    public function getGlobals()
    {
        return array(
            'raygun_api_key' => $this->apiKey,
            'raygun_app_version' => $this->version,
            'raygun_default_tags' => $this->defaultTags
        );
    }

    public function getName()
    {
        return 'nietonfir_raygun_setup';
    }
}
