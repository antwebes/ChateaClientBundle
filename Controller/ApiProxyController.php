<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Guzzle\Http\Exception\ClientErrorResponseException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiProxyController extends Controller
{
    /**
     * @return \Ant\Bundle\ChateaClientBundle\Http\ApiRequestAllow
     */

    private function getApiRequestAllow()
    {
        return $this->container->get('antwebes_chateaclient_bundle.http.api_request_allow');
    }

    /**
     * Resolve api calls
     *
     * @param Request $request
     *
     * @return Response
     */
    public function apiAction(Request $request)
    {
        if ($request->isXmlHttpRequest() or $request->headers->get('Content-Type') =='application/json') {
            $pathInfo = $request->getPathInfo();
            if(!$this->getApiRequestAllow()->isAllow($pathInfo)){
                throw new NotFoundHttpException('No route allow for " '.$request->getMethod().' '.$pathInfo.'"');
            }
            $query_string = null;
            if ($request->server->get('QUERY_STRING')){
                $query_string = '?' .$request->server->get('QUERY_STRING');
            }

            $url_api = $pathInfo . $query_string;

            /*
             * Only is configurable the first folder
             * internalapi/users/username-available
             * is changed to api/users/username-available
             */
            $array = explode("/", $url_api);
            unset($array[1]);
            $text = implode("/", $array);

            $url_api = "/api".$text;

            $apiUri = trim($url_api,'/');
            try{
                return $this->container->get('antwebes_chateaclient_bundle.http.api_client')->sendRequest('GET',$apiUri);
            }catch (ClientErrorResponseException $e){
                $headersGuzzle = $e->getResponse()->getHeaders()->toArray();
                $headersSymfony = array();
                foreach($headersGuzzle as $key=>$value){
//this header is not suporter in symfony
                    if($key != 'Transfer-Encoding' && $value !== 'chunked'){
                        $headersSymfony[$key] = $value[0];
                    }
                }
                return new Response($e->getResponse()->getBody(true),
                    $e->getResponse()->getStatusCode(),
                    $headersSymfony
                );
            }
        }else{
            throw new NotFoundHttpException();
        }
    }
}