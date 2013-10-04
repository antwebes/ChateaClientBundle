<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\ChateaClient\Client\ApiException;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;
use Ant\Bundle\ChateaClientBundle\Model\Registration;
use Ant\Bundle\ChateaClientBundle\Form\RegistrationType;
use Ant\Bundle\ChateaClientBundle\Form\UserFormType;

/**
 * Channel controller.
 *
 */
class UserController extends  BaseController
{

    public function whoamiAction()
    {
        $user = $this->getUserRepository()->whoami();
        return $this->render("ChateaClientBundle:User:whoami.html.twig",array('user'=>$user));
    }

    /**
     * Lists all Channels entities.
     *
     */
    public function indexAction()
    {

        try{
            $users = $this->getUserRepository()->findAll();
            return $this->render('ChateaClientBundle:User:index.html.twig', array(
                    'users' => $users
                )
            );
        }catch (ApiException $ex)
        {
            $errors = $this->parseApiException($ex);

            return $this->render('ChateaClientBundle:Default:error.html.twig',
                array('errors' => $errors,
                    'return'=>$this->generateUrl('antwebes_chateaclient_api')));
        }

    }

    /**
     * Finds and displays a Channel entity.
     *
     */
    public function showAction($user_id)
    {
        if (!$user_id) {
            throw $this->createNotFoundException('UserController::showAction() ERROR : Not found user_id');
        }
        $user = null;

        try{
            $entity = $this->getUserRepository()->findById($user_id);
        }catch (ApiException $ex)
        {
            $errors = $this->parseApiException($ex);

            return $this->render('ChateaClientBundle:Default:error.html.twig',
                array('errors' => $errors,
                     'return'=>$this->generateUrl('antwebes_chateaclient_user_show', array('user_id' => $user_id))));

        }
        return $this->render('ChateaClientBundle:User:show.html.twig', array(
                'user' => $entity
            )
        );
    }
    public function newAction()
    {
        $this->registerAction();
    }

    public function registerAction()
    {
        $registration = new Registration();
        $form = $this->createForm(new RegistrationType(), $registration, array(
                'action' => $this->generateUrl('antwebes_chateaclient_user_create'),
            ));

        return $this->render(
            'ChateaClientBundle:User:register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function createAction(Request $request)
    {

        $form = $this->createForm(new RegistrationType(), new Registration());

        $form->handleRequest($request);
        $entity = new User();

        try{
            if ($form->isValid()) {
                $registration = $form->getData();

                $this->getUserRepository()->save($entity);

                return $this->render('ChateaClientBundle:User:show.html.twig', array(
                        'user' => $entity
                    )
                );
            }
        }catch (ApiException $ex){
            $errors = $this->parseApiException($ex);

            return $this->render('ChateaClientBundle:Default:error.html.twig',
                array('errors' => $errors,
                    'return'=>$this->generateUrl('antwebes_chateaclient_user_new')));
        }

        return $this->render(
            'AcmeAccountBundle:Account:register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function editAction($user_id)
    {
        if (!$user_id) {
            throw $this->createNotFoundException('UserController::showAction() ERROR : Not found user_id');
        }

        try{
            $entity = $this->getUserRepository()->findById($user_id);
            //FIXME: Update this with $me = $this->getUser()
            $me = $this->getUserRepository()->whoami();

            if(($entity->getId()) !== ($me->getId())){

                return $this->sendError("You do not have permissions for use this function.",
                        'antwebes_chateaclient_channel_show_all');
            }

        }catch (ApiException $ex)
        {
            $errors = $this->parseApiException($ex);

            return $this->render('ChateaClientBundle:Default:error.html.twig',
                array('errors' => $errors, 'return'=>$this->generateUrl('antwebes_chateaclient_channel_show_all')));
        }

        $editForm = $this->createForm(new UserFormType(), $entity,array(
                'method' => 'PATCH',
            ));


        return $this->render('ChateaClientBundle:User:edit.html.twig', array(
                'entity' => $entity,
                'edit_form' => $editForm->createView()
            ));
    }

    public function updateAction($user_id)
    {
        $request = $this->getRequest();
        $editForm = null;

        try{

            $entity = $this->getUserRepository()->findById($user_id);

            $editForm = $this->createForm(new UserFormType(), $entity,array(
                    'method' => 'PATCH',
                ));

            $editForm->handleRequest($request);

            if ($editForm->isValid()) {

                $this->getUserRepository()->update($entity);

                return $this->redirect($this->generateUrl('antwebes_chateaclient_user_show', array('user_id' => $entity->getId())));
            }

            return $this->render('ChateaClientBundle:User:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
                ));

        }catch (ApiException $ex)
        {
            $errors = $this->parseApiException($ex);

            return $this->render('ChateaClientBundle:Default:error.html.twig',
                array('errors' => $errors, 'return'=>$this->generateUrl('antwebes_chateaclient_channel_show_all')));
        }

    }

}