<?php

namespace Xenokore\ComponentLoader\Tests;

use PHPUnit\Framework\TestCase;
use Xenokore\ComponentLoader\Loader;
use Xenokore\ComponentLoader\Tests\Data\Author\Package\TestClass;

class LoaderTest extends TestCase
{

    private $loader;

    protected function setUp(): void
    {
        $loader = new Loader(__DIR__ . '/data/vendor');
        $this->assertInstanceOf(Loader::class, $loader);

        $this->loader = $loader;
    }

    public function testContainer()
    {
        $container_definitions = $this->loader->getContainerDefinitions();

        $this->assertIsArray($container_definitions);
        $this->assertCount(1, $container_definitions);
        $this->assertArrayHasKey(TestClass::class, $container_definitions);

        // Mock how a class is loaded using the container
        $test_class = $container_definitions[TestClass::class]($container_definitions);

        $this->assertInstanceOf(TestClass::class, $test_class);
        $this->assertEquals('success', $test_class->getTestVar());
    }

    protected function testTemplates()
    {
        $this->markTestSkipped(
            'Templates not yet implemented'
        );
    }
}
