RaygunBundle
============

[Raygun4PHP](https://github.com/MindscapeHQ/raygun4php) is a [Raygun.io](https://raygun.io) provider for PHP 5.3+.
[Raygun4js](https://github.com/MindscapeHQ/raygun4js) is a is a [Raygun.io](https://raygun.io) plugin for JavaScript.
This bundle registers the library with the framework and provides a twig template for the plugin.

[![Latest Stable Version](https://poser.pugx.org/nietonfir/raygun-bundle/v/stable.svg)](https://packagist.org/packages/nietonfir/raygun-bundle) [![Latest Unstable Version](https://poser.pugx.org/nietonfir/raygun-bundle/v/unstable.svg)](https://packagist.org/packages/nietonfir/raygun-bundle) [![License](https://poser.pugx.org/nietonfir/raygun-bundle/license.svg)](https://github.com/Nietonfir/RaygunBundle/blob/master/LICENSE)

Installation
------------

Install the latest version with

```
$ composer require nietonfir/raygun-bundle
```

Configuration
-------------

Add your raygun api-key in parameters.yml:

```yaml
# app/config/parameters.yml
parameters:
    […]
    raygun_api_key: <your_raygun_api-key>
```

Update `config.yml` with the following configuration:

```yaml
# app/config/config.yml
nietonfir_raygun:
    api_key: %raygun_api_key%
```

Enable the bundle:

```php
// app/AppKernel.php
$bundles = [
    […]
    new Nietonfir\RaygunBundle\NietonfirRaygunBundle(),
];
```

Basic Usage
-----------

Register the raygun monolog handler in `config_prod.yml` as the first monolog handler.

```yaml
# app/config/config_prod.yaml
monolog:
    handlers:
        raygun:
            type: service
            id:   raygun.handler
        main:
            type:         fingers_crossed
            action_level: error
            handler:      nested
```

To use the [JavaScript client](https://raygun.io/docs/languages/javascript) include the bundled views in your template at their designated places according to the raygun documentation. [`NietonfirRaygunBundle:Static:raygun-js.html.twig`](Resources/views/Static/raygun-js.html.twig) provides the javascript client and [`NietonfirRaygunBundle::setup.html.twig`](Resources/views/setup.html.twig) configures it, e.g.:

```twig
{# snip #}
{% include 'NietonfirRaygunBundle:Static:raygun-js.html.twig' %}
</head>
<body>

{# snip #}
{% include 'NietonfirRaygunBundle::setup.html.twig' %}
</body>
```

If you wish to override any part of the templates you can use the default Symfony mechanisms. A global twig parameter (`raygun_api_key`) is exposed by a custom `Twig_Extension` that provides the API key.
Raygun pulse can be enabled by either setting or passing a truthy variable named `enable_pulse` to the template:

```twig
{% include 'NietonfirRaygunBundle::setup.html.twig' with {'enable_pulse': true} only %}
```
