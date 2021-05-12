<?php

namespace Xenokore\ComponentLoader;

use Xenokore\ComponentLoader\Exception\InvalidVendorDirException;

class Loader
{

    private $component_dirs;

    private $container_definitions = [];

    private $templates = [];

    public const IGNORED_VENDOR_DIRS = [
        '.',
        '..',
        'bin',
        'composer',
        'doctrine',
        'phar-io',
        'phpdocumentor',
        'phpspec',
        'phpunit',
        'symfony',
        'laravel',
    ];

    public const IGNORED_VENDOR_SUB_DIRS = [
        '.',
        '..',
        'bin',
        'cache',
    ];

    public function __construct(string $composer_vendor_dir)
    {
        $this->initComponents($composer_vendor_dir);

        return $this;
    }

    /**
     * Initialize components in a vendor directory.
     * Mostly used only once to load components in the composer vendor directory.
     *
     * @param string $vendor_dir
     * @return void
     */
    public function initComponents(string $vendor_dir)
    {
        // Check if vendor directory is readable
        if (!\is_dir($vendor_dir) || !\is_readable($vendor_dir)) {
            throw new InvalidVendorDirException(
                "Component vendor dir is not readable: {$vendor_dir}"
            );
        }

        // Iterate vendor dirs
        foreach (new \IteratorIterator(new \DirectoryIterator($vendor_dir)) as $file_info) {
            if ($file_info->isDir() && !\in_array($file_info->getFilename(), self::IGNORED_VENDOR_DIRS)) {

                // Iterate vendor sub dirs
                $vendor_author_dir = $file_info->getRealPath();
                foreach (new \IteratorIterator(new \DirectoryIterator($vendor_author_dir)) as $file_info) {
                    if ($file_info->isDir() && !\in_array($file_info->getFilename(), self::IGNORED_VENDOR_SUB_DIRS)) {

                        // Get component dir
                        $component_dir = $file_info->getRealPath() . '/component';
                        if (\is_dir($component_dir) && \is_readable($component_dir)) {

                            $this->component_dirs[] = $component_dir;

                            // Check for container definitions
                            $container_file = $component_dir . '/container.php';
                            if (\file_exists($container_file) && \is_readable($container_file)) {

                                $definitions = include $container_file;
                                if (\is_array($definitions) && !empty($definitions)) {
                                    foreach ($definitions as $key => $value) {
                                        $this->container_definitions[$key] = $value;
                                    }
                                }
                            }

                            // Check for templates
                            $templates_file = $component_dir . '/templates.php';
                            if (\file_exists($templates_file) && \is_readable($templates_file)) {

                                // TODO: implement templates
                            }
                        }
                    }
                }
            }
        }
    }

    public function getContainerDefinitions(): array
    {
        return $this->container_definitions;
    }

    public function getTemplates(): array
    {
        return $this->templates;
    }
}
