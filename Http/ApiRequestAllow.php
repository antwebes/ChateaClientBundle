<?php
/**
 * Created by Javier Fernández Rodríguez for Ant-Webs S.L.
 * User: Xabier <jbier@gmail.com>
 * Date: 18/12/14 - 11:04
 */

namespace Ant\Bundle\ChateaClientBundle\Http;

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;


/**
 * Class ApiRequestAllow
 * @package Ant\CoreBundle\Http
 */
class ApiRequestAllow
{
    private $paths;

    public function __construct($paths)
    {
        $this->paths = $paths;
    }

    public function isAllow($uri)
    {
        $matcher = new UrlMatcher($this->getRouteCollection(),new RequestContext());

        try{
            return $matcher->match($uri) != null;
        }catch (MethodNotAllowedException $e){
            return false;
        }catch (ResourceNotFoundException $e){
            return false;
        }
    }

    private function getRouteCollection()
    {
        $routes = new RouteCollection();
        $i=0;

        foreach($this->paths as $path){
            $route = new Route($path);
            $routes->add('route_name_'.$i++,$route);
        }

        return $routes;
    }
}