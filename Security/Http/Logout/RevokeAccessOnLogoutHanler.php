<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Http\Logout;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Ant\ChateaClient\Service\Client\ChateaGratisAppClient;
use Ant\Bundle\ChateaClientBundle\Security\User\User;

class RevokeAccessOnLogoutHanler implements LogoutHandlerInterface
{
    private $securityContext;
    private $client;

    function __construct(SecurityContextInterface $securityContext, ChateaGratisAppClient $client)
    {
        $this->securityContext = $securityContext;
        $this->client = $client;
    }

    /**
     * This method is called by the LogoutListener when a user has requested
     * to be logged out. Usually, you would unset session variables, or remove
     * cookies, etc.
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $token = $this->securityContext->getToken();

        if($this->mustRevokeToken($token)){
            $this->revokeAccessToken($token);
        }
    }

    private function mustRevokeToken($token)
    {
        return $token != null && $token->getUser() instanceof User;
    }

    private function revokeAccessToken($token)
    {
        try {
            $user = $token->getUser();
            $this->client->updateAccessToken($user->getAccessToken());
            $this->client->revokeToken();
        }catch(\Exception $e){

        }
    }
}