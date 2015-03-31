<?php
/**
 * Created by Javier Fernández Rodríguez for Ant-Webs S.L.
 * User: Xabier <jbier@gmail.com>
 * Date: 11/04/14 - 9:47
 */

namespace Ant\Bundle\ChateaClientBundle\Util;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

/**
 * This class load Yaml parametetes files.
 * Class YamlFileLoader
 * @package Chatea\NodeServicesBundle\Service
 */
class YamlFileLoader extends FileLoader
{

    /**
     * @var array $parameters;
     */
    protected $parameters;
    protected $file;
    protected $header;
    /**
     * Crate new instance of YamlFileLoader
     * @param \Symfony\Component\Config\FileLocatorInterface $root_dir the directory into file exits.
     * @param $file The fiel name to load
     * @param $header the name of header for example parameters: or security: ...
     */
    public function __construct($root_dir, $file, $header = 'parameters')
    {
        parent::__construct(new FileLocator($root_dir));
        $this->file = $file;
        $this->header = $header;
        $this->parameters = $this->load($file);
    }

    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     * @param string $type The resource type¡
     */
    /**
     * @param mixed $resource
     * @param null $type
     * @return array|bool|float|int|mixed|null|number|string
     * @throws \InvalidArgumentException
     */
    function load($resource, $type = null)
    {

        $ymlUserFiles = $this->getLocator()->locate($resource, null, false);
        if(count($ymlUserFiles) < 1){
            throw new \InvalidArgumentException("This $resource  not found ");
        }
        $collection = Yaml::parse($ymlUserFiles[0]);
        if(!empty($this->header)){
            if ($collection == null || !array_key_exists($this->header, $collection)){
                return array();
            };

            return $collection[$this->header];
        }

        return $collection;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param string $type The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }

    /**
     * @param $name The name of parameter
     *
     * @return array|bool|float|int|mixed|null|number|string
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException
     * This exception is throw if paramenter not exits
     */
    public function getParameter($name)
    {
        if (!$name || !array_key_exists($name, $this->parameters)) {
            throw new ParameterNotFoundException($name);
        }
        return $this->parameters[$name];
    }

    /**
     * Return array all prameters loaded
     * @return array the list of parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}