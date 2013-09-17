<?php
namespace Ant\Bundle\ChateaClientBundle\Security\EntryPoint;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface 
{
	
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $response = new Response();
        $response->headers->set('WWW-Authenticate', sprintf('Basic realm="realname"'));
        $response->setStatusCode(401);

        return $response;
    }

}
