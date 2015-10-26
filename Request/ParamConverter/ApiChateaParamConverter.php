<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 27/10/15
 * Time: 9:05
 */

namespace Ant\Bundle\ChateaClientBundle\Request\ParamConverter;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;
use Ant\Bundle\ChateaClientBundle\Manager\FactoryManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiChateaParamConverter implements ParamConverterInterface
{
    private $apiManager;

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    /**
     * Stores the object in the request.
     *
     * @param Request        $request       The request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();
        $class = $configuration->getClass();
        $options = $configuration->getOptions();
        $managerMethod = $options['method'];
        $methodParams = $options['methodParams'];
        $paramsValues = $this->extractValuesFromRequest($methodParams, $request);
        $manager = $this->getManager($options['manager']);
        $object = $this->getObject($manager, $managerMethod, $paramsValues);

        if (null === $object && false === $configuration->isOptional()) {
            throw new NotFoundHttpException(sprintf('%s object not found.', $class));
        }

        $request->attributes->set($name, $object);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        if(null === $configuration->getClass()){
            return false;
        }

        $reflector = new \ReflectionClass($configuration->getClass());

        return $reflector->isSubclassOf('Ant\Bundle\ChateaClientBundle\Api\Model\BaseModel');
    }

    protected function getManager($managerName)
    {
        return FactoryManager::get($this->apiManager, $managerName);
    }

    protected function extractValuesFromRequest($methodParams, Request $request)
    {
        return array_map(function ($param) use ($request){
            return $request->get($param);
        }, $methodParams);
    }

    protected function getObject($manager, $managerMethod, $paramsValues)
    {
        return call_user_func_array(array($manager, $managerMethod), $paramsValues);
    }
}