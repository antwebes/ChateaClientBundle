<?php
/**
 * Created by PhpStorm.
 * User: ant4
 * Date: 12/03/15
 * Time: 9:42
 */

namespace Ant\Bundle\ChateaClientBundle\Http;

use InvalidArgumentException;
use Guzzle\Http\Client;
use Guzzle\Http\Message\RequestInterface;

use Symfony\Component\HttpFoundation\Response;

use  Ant\ChateaClient\Service\Client\StoreInterface;
use Ant\ChateaClient\Service\Client\AuthenticationException;


/**
 * Class ApiClient
 * @package Ant\CoreBundle\Http
 */
class ApiClient extends Client
{
    private static $httMethods = array('OPTIONS','GET','HEAD','POST','PUT','DELETE','TRACE','CONNECT');

    /**
     * @var StoreInterface
     */
    private $store;

    /**
     * @var string
     */
    private $clientId;
    /**
     * @var string
     */
    private $secret;

    /**
     * Create new http client connected to api
     *
     * @param string $baseUrl
     * @param StoreInterface $store
     * @param string $environment
     */
    public function __construct($baseUrl, StoreInterface $store, $clientId, $secret, $environment = 'dev')
    {
        parent::__construct($baseUrl);
        $this->store = $store;
        $this->clientId = $clientId;
        $this->secret = $secret;
    }

    /**
     * Get access token for access to api
     * @return string the token for access to api
     */
    protected function getAccessToken()
    {
        return $this->requestAccessToken();
    }

    /**
     * Create and return a new {@see Guzzle\Http\Message\RequestInterface} configured for the client.
     *
     * Use an absolute path to override the base path of the client, or a relative path to append to the base path of
     * the client. The URI can contain the query string as well. Use an array to provide a URI template and additional
     * variables to use in the URI template expansion.
     *
     * @param string                                    $method  HTTP method. Defaults to GET
     * @param string|array                              $uri     Resource URI.
     * @param array|\Guzzle\Common\Collection           $headers HTTP headers
     * @param string|resource|array|EntityBodyInterface $body    Entity body of request (POST/PUT) or response (GET)
     * @param array                                     $options Array of options to apply to the request
     *
     * @return \Guzzle\Http\Message\RequestInterface
     * @throws \Guzzle\Common\Exception\InvalidArgumentException if a URI array is passed that does not contain exactly two elements: the URI
     *                                  followed by template variables
     */
    public function createRequest($method = 'GET', $uri = null, $headers = null, $body = null, array $options = array())
    {
        if(!in_array(strtoupper($method),self::$httMethods)){
            throw new \InvalidArgumentException("The method $method is not supported");
        }

        $request = parent::createRequest($method,$uri,$headers,$body,$options);

        $request->setHeader('Authorization ','Bearer '. $this->getAccessToken());

        if($request->getHeader('Accept') == null){
            $request->setHeader('Accept','application/json');
        }

        return $request;
    }

    /**
     * This method wrapper Guzzle response in symfony response
     *
     * @param RequestInterface $request the guzzle request
     * @return Response the symfony response,
     */
    public function handle(RequestInterface $request)
    {
        $response = $request->send();
        $headers = $response->getHeaders()->getAll();
        $body = $response->getBody(true);
        $statusCode = $response->getStatusCode();

        $symfonyHeaders = array();

        foreach ($headers as $keys) {
            /**
             * Transfer-Encoding type chunked is not suported by symfony response
             */
            if($keys->getName() == "Transfer-Encoding" && $keys->__toString() === 'chunked'){
                continue;
            }
            $symfonyHeaders[$keys->getName()] = $keys->__toString();
        }

        return new Response($body,$statusCode,$symfonyHeaders);
    }

    /**
     * Send a request from client for the api
     *
     * @param string           $method  HTTP method. Defaults to GET
     * @param string|array     $uri     Resource URI
     * @param array|Collection $headers HTTP headers
     *                                  location. Use the 'body' option instead for forward compatibility.
     * @return Response
     * @see   \Symfony\Component\HttpFoundation\Response
     */
    public function sendRequest($method = 'GET', $uri = null, $headers = null, $body = null)
    {
        $request = $this->createRequest($method,$uri,$headers,$body);
        return $this->handle($request);
    }

    /**
     * This retrieve the access token in store or in server
     *
     * @return string the access token
     */
    private function requestAccessToken()
    {
        // if not exits data in the store
        if(!$this->store->getPersistentData('token_expires_at')){
            return $this->getAccessTokenWithClientCredentials();
            // if access token expires.
        }else if($this->store->getPersistentData('token_expires_at') < time()){
            try{
                return $this->getAccessTokenWithRefreshToken();
            }catch(AuthenticationException $e){
                return $this->getAccessTokenWithClientCredentials();
            }
        }else{
            return $this->store->getPersistentData('access_token');
        }
    }

    private function getAccessTokenWithClientCredentials()
    {
        $request = parent::createRequest('POST',
            'oauth/v2/token',
            array('Accept','application/json'),
            array('grant_type'=>'client_credentials',
                'client_id'=>$this->clientId,
                'client_secret' => $this->secret
            )
        );

        $response = $request->send();
        $authData = $response->json();
        $this->persistAuthData($authData);
        return $authData['access_token'];
    }


    private function getAccessTokenWithRefreshToken()
    {
        $refresh_token = $this->store->getPersistentData('token_refresh');

        if (!is_string($refresh_token) || 0 >= strlen($refresh_token)) {
            throw new InvalidArgumentException("refresh_token must be a non-empty string");
        }

        $request = parent::createRequest('POST',
            'oauth/v2/token',
            array('Accept','application/json'),
            array('grant_type'=>'refresh_token',
                'client_id'=>$this->clientId,
                'client_secret' => $this->secret,
                'refresh_token' => $refresh_token
            )
        );

        $response = $request->send();
        $authData = $response->json();
        $this->persistAuthData($authData);
        return $authData['access_token'];

    }

    private function persistAuthData($authData)
    {
        $this->store->setPersistentData('access_token',$authData['access_token']);
        $this->store->setPersistentData('token_refresh',$authData['refresh_token']);
        $this->store->setPersistentData('token_expires_at',$authData['expires_in'] + time());
    }
}