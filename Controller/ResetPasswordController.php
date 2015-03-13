<?php
/**
 * Created by PhpStorm.
 * User: ant4
 * Date: 13/03/15
 * Time: 10:36
 */

namespace Ant\Bundle\ChateaClientBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

use Ant\Bundle\ChateaClientBundle\Form\ResetPasswordType;
use Ant\Bundle\ChateaClientBundle\Form\Model\ResetPassword;

/**
 * Class ProfileController
 * @package Ant\UserBundle\Controller
 */
class ResetPasswordController extends Controller
{
    public function resetAction()
    {
        $form = $this->createForm(new ResetPasswordType());

        return $this->render('ChateaClientBundle:ResetPassword:reset.html.twig', array('form' => $form->createView()));
    }

    public function doResetAction(Request $request)
    {
        $resetPassword = new ResetPassword();
        $users = $this->get('api_users');
        $form = $this->createForm(new ResetPasswordType(), $resetPassword);

        $form->submit($request);

        if($form->isValid()){
            try {
                $users->forgotPassword($resetPassword->getEmail());

                $response = $this->render('ChateaClientBundle:ResetPassword:success.html.twig');


                return $response;

            } catch(\Exception $e) {
                try {
                    $this->addErrorsToFormFromException($form, $e);
                } catch(\Exception $ex) {
                    $response = $this->render('ChateaClientBundle:ResetPassword:error.html.twig', array('exception' => $ex));

                    //$response->setSharedMaxAge($this->container->getParameter('user_bundle_reset_do_reset'));

                    return $response;
                }
            }
        }

        return $this->render('ChateaClientBundle:ResetPassword:reset.html.twig', array('form' => $form->createView()));
    }

    public function forgotPasswordAction()
    {
        $response = $this->render('UserBundle:ResetPassword:forgotPassword.html.twig');

        $response->setMaxAge($this->container->getParameter('user_bundle_reset_forgot_password'));
        $response->setPrivate();

        return $response;

    }

    private function addErrorsToFormFromException($form, $e)
    {
        //TODO: esto esta acoplado al formato del error que llega desde la libreira: ojo chapuza
        $translator = $this->get('translator');
        $serverError = json_decode($e->getMessage());
        $tranlatedError = $translator->trans("%%error%%", array("%%error%%" => $serverError->errors));

        $form->get('email')
            ->addError(new FormError($tranlatedError));
    }
}