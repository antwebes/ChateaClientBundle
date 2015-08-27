<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/vendor/autoload.php';

AnnotationRegistry::registerAutoloadNamespace('Ant\Bundle\ChateaClientBundle', __DIR__ . '/../../..');

return $loader;
