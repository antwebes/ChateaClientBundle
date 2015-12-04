ChateaClientBundle
==================

Symfony2 bundle for ChateaClient library, It makes it easy to use API of api.chatea.net

Install
-------

1) Añadir Bundle a AppKernel.php ( ChateaClientBundle y ChateaSecureBundle )

    new  Ant\Bundle\ChateaClientBundle\ChateaClientBundle()
    new Ant\Bundle\ChateaSecureBundle\ChateaSecureBundle()
    
2) Añadir no ficheiro de configuracion  ou no routing.yml

```
antwebes_chateclient:
    resource: '@ChateaClientBundle/Resources/config/routing.xml'
    prefix:   /  
``` 
    
3) Configurar client_id y secret de la aplicación en app/config/config.yml:

```
chatea_secure:
    app_auth:
        client_id: %chatea_client_id%
        secret: %chatea_secret_id%
        enviroment: %chatea_enviroment%
    api_endpoint: %api_endpoint%

chatea_client:
    app_auth:
        client_id: %chatea_client_id%
        secret: %chatea_secret_id%
    api_endpoint: %api_endpoint%
    app_id: %chatea_app_id%
    authenticate_client_as_guest: true|false #optional (by deafault false) to indicate if client authenticates as guest. In case of false it authenticates with client credentials
    visits_limit: INTEGER #optional (by default 3) the number of the last N visits to show in the welcome login page
    wellcome_channels_limit: INTEGER #optional (by default 9) the number of channels (fan, owner, moderator) to show in the welcome login page
    
beelab_recaptcha2:
    site_key:  %recaptcha_public_key%
    secret: %recaptcha_private_key%
    enabled: %recaptcha_enabled%
```

4) Configurar el firewall en app/config/security.yml:
```
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
```

Autenticación
-------------

Por defecto todas las llamadas a apichatea se hacen con el usario de la aplicación. 

Si desea realizar una llamada a apichatea que requiere un usuario logueado use la anotación @APIUser.

Está implementada en:
>Ant\Bundle\ChateaClientBundle\EventListener\AuthTokenUpdaterListener;

Dicha anotación averiguará si el usuario está autenticado y en caso de ser así realizara las llamadas 
a apichatea con el usuario logueado. Ejemplo:

```
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
```

Si todos los médodos de un controlador requieren un usuario logueado puede poner la anotación en la clase. Ejemplo:


```
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
```

Dicha annotación es gestionada por el listener ```Ant\Bundle\ChateaClientBundle\EventListener\AuthTokenUpdaterListener``` que escucha al evento del controlador para averiguar si debe actualizar el access token del usuario en caso necesario.

Make wellcome login page work
----

In order to make the wellcome login page work, you have to configure the twig globals ```boilerplate_users_base_url``` and  ```boilerplate_channels_base_url```. For example:

```
twig:
    globals:
        boilerplate_users_base_url: http://myboilerplate.com/users
        boilerplate_channels_base_url: http://myboilerplate.com/channels
```

To redirect the user after login to the wellcome page configure in your firewall the ```default_target_path``` to ```chatea_client_wellcome``` and the ```always_use_default_target_path``` to ```true```