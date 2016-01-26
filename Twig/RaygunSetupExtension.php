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

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getGlobals()
    {
        return array(
            'raygun_api_key' => $this->apiKey
        );
    }

    public function getName()
    {
        return 'nietonfir_raygun_setup';
    }
}
