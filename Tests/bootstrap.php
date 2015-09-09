<?php
/*
 * This file is part of the ChateaClientBundle package.
 *
 * (c) ChateaClientBundle <http://github.com/antwebes/chateaClientBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$file = __DIR__.'/../../../../../../autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}
$autoload = require_once $file;
use Doctrine\Common\Annotations\AnnotationRegistry;
AnnotationRegistry::registerLoader(function ($class) {
    if (strpos($class, 'Ant\\Bundle\\ChateaClientBundle\\Security\\Authentication\\Annotation\\') === 0) {
        $path = __DIR__.'/../'.str_replace('\\', '/', substr($class, strlen('Ant\Bundle\ChateaClientBundle\\'))).'.php';
        require_once $path;
    }
    return class_exists($class, false);
});