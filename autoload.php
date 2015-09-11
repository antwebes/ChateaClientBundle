<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/vendor/autoload.php';

AnnotationRegistry::registerFile(__DIR__.'/Security/Authentication/Annotation/APIUser.php');

return $loader;
