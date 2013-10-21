ChateaClientBundle
==================

Symfony2 bundle for ChateaClient library, It makes it easy to use API of api.chatea.net

Install 
1) Añadir Bundle a AppKernel.php
    new  Ant\Bundle\ChateaClientBundle\ChateaClientBundle()
    
2) Añadir no ficheiro de configuracion  ou no routing.yml

antwebes_chateclient:
    resource: '@ChateaClientBundle/Resources/config/routing.xml'
    prefix:   /  
    
    
    first commit 