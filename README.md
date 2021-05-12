Xeno Component Loader
=====================

A library that helps with loading Xeno compatible components.
Libraries that are compatible will be able to easily expose autowire
definitions for a DI container, expose Twig templates, etc...

## Installation

```
composer require xenokore/component-loader
```

## Usage

```php
$component_loader = new Xenokore\ComponentLoader\Loader('/vendor');

// When using PHP-DI
$builder = new DI\ContainerBuilder();
$builder->useAnnotations(true);
$builder->useAutowiring(true);

$builder->addDefinitions(
    $component_loader->getContainerDefinitions()
);
```

## Creating a component

1. Create a `/component` directory in the root of your component library
2. Add your DI container definitions in `/component/container.php`
3. Require your component using composer in a project or framework that uses the **Xeno Component Loader**
4. Your definitions will be automatically added to the main DI container

## Status

Currently only container definitions are supported. Twig templates still have to be added.
And there will most likely be more features than this.
