<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Http\RememberMe;

use Ant\Bundle\ChateaClientBundle\Security\User\User;
use Symfony\Component\Security\Http\RememberMe\AbstractRememberMeServices;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

class AccessTokenBasedRememberMeService extends AbstractRememberMeServices
{
    protected function processAutoLoginCookie(array $cookieParts, Request $request)
    {
        if(count($cookieParts) !== 2) {
            throw new AuthenticationException('The cookie is invalid.');
        }

        list($accessToken, $expiresAt) = $cookieParts;

        $authUserData = array(
                'access_token' => $accessToken,
                'refresh_token' => '',
                'token_type' => '',
                'expires_in' => $expiresAt - time(),
                'scope' => ''
            );
            
        $user = $this->mapJsonToUser($authUserData, '');

        if(!$user->isCredentialsNonExpired()){
            throw new AuthenticationException('The cookie is invalid.');    
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    protected function onLoginSuccess(Request $request, Response $response, TokenInterface $token)
    {
        $user = $token->getUser();
        $expires = time() + $this->options['lifetime'];
        $value = $this->generateCookieValue(
            $user->getAccessToken(),
            $user->getExpiresAt()
            );

        $response->headers->setCookie(
            new Cookie(
                $this->options['name'],
                $value,
                $expires,
                $this->options['path'],
                $this->options['domain'],
                $this->options['secure'],
                $this->options['httponly']
            )
        );
    }

    protected function generateCookieValue($accessToken, $expiresAt)
    {
        return $this->encodeCookie(array(
            $accessToken,
            $expiresAt
        ));
    }

    protected function mapJsonToUser($data, $username)
    {
        return new User(
            $username,
            $data['access_token'],
            $data['refresh_token'],
            $data['token_type'],
            $data['expires_in'],
            explode(',', $data['scope'])
        );
    }
}