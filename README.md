ChateaClientBundle
==================

Symfony2 bundle for ChateaClient library, It makes it easy to use API of api.chatea.net

Install
-------

1) Añadir Bundle a AppKernel.php
    new  Ant\Bundle\ChateaClientBundle\ChateaClientBundle()
    
2) Añadir no ficheiro de configuracion  ou no routing.yml

antwebes_chateclient:
    resource: '@ChateaClientBundle/Resources/config/routing.xml'
    prefix:   /  
    
    
3) Configurar client_id y secret de la aplicación en app/config/config.yml:

chatea_client:
    app_auth:
        client_id: id_del_cliente
        secret: secret_del_cliente

4) Configurar el firewall en app/config/security.yml:

security:
    providers:
        antwebs_chateaclient_provider:
            id: antwebs_chateaclient_user_provider

    firewalls:
        dev:
            pattern:  ^/(_(profiler|wdt)|css|images|js)/
            security: false

        login:
            pattern:  ^/auth/login$
            security: false


        secured_area:
            pattern:    ^/
            anonymous: ~
            antwebs_chateaclient_login:
                check_path: _security_check
                login_path: _antwebes_chateaclient_login
                provider: antwebs_chateaclient_provider
            logout:
                invalidate_session: false
                path:   _antwebes_chateaclient_logout
                target: nombre_ruta_a_derigir_tras_logout
                handlers: [antwewebs_revoke_access_on_logout_handler]

Autenticación
-------------

Por defecto todas las llamadas a apichatea se hacen con el usario de la aplicación. 

Si desea realizar una llamada a apichatea que requiere un usuario logueado use la anotación @APIUser.
Dicha anotación averiguará si el usuario está autenticado y en caso de ser así realizara las llamadas 
a apichatea con el usuario logueado. Ejemplo:

`
namespace Acme\MiBundel\Controller;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;

class UsuarioController
{
    /**
     * @APIUser
     *
     */
    public function actualizarNombreUsuarioAction()
    {
        //código
    }
}
`

Si todos los médodos de un controlador requieren un usuario logueado puede poner la anotación en la clase. Ejemplo:

`
namespace Acme\MiBundel\Controller;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;

/**
 * @APIUser
 *
 */
class AreaRestringidaController
{
    public function accion1Action()
    {
        //código
    }

    public function accion2Action()
    {
        //código
    }
}
`