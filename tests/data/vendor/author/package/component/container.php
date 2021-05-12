<?php

namespace Xenokore\ComponentLoader\Tests\Data\Author\Package;

return [
    TestClass::class => function ($container) {
        return new TestClass();
    },
];
