RaygunBundle
============

[Raygun4PHP](https://github.com/MindscapeHQ/raygun4php) is a [Raygun.io](https://raygun.io) provider for PHP 5.3+.
[Raygun4js](https://github.com/MindscapeHQ/raygun4js) is a is a [Raygun.io](https://raygun.io) plugin for JavaScript.
This bundle registers the library with the framework and provides a twig template for the plugin.

[![License](https://poser.pugx.org/nietonfir/raygun-bundle/license.svg)](https://github.com/Nietonfir/RaygunBundle)

Installation
------------

Install the latest version with

```
$ composer require nietonfir/raygun-bundle mindscape/raygun4php
```

Requiring the raygun library is essential since no stable version is currently available.

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

Register the raygun monolog handler in `config_prod.yml` as first monolog handler.

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
