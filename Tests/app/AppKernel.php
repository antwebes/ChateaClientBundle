<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 17/09/15
 * Time: 13:39
 */
namespace Ant\Bundle\ChateaClientBundle\Tests\app;

use Symfony\Bundle\AsseticBundle\AsseticBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

use Ant\Bundle\ChateaClientBundle\ChateaClientBundle;
use Ant\Bundle\ChateaSecureBundle\ChateaSecureBundle;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new SecurityBundle(),
            new TwigBundle(),
            new ChateaClientBundle(),
            new ChateaSecureBundle(),
            new AsseticBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $file = 'config';
        if ('test' === $this->getEnvironment()) {
            $file .= '_test';
        }
        $loader->load(__DIR__."/config/$file.yml");
    }
}