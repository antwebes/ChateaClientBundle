<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Intl\Exception\MethodNotImplementedException;

class BaseController extends Controller
{
	protected function addErrorsToForm($e, $form, $context = '_')
	{
		$translator = $this->get('translator');
	
		if ($this->isJson($e->getMessage())){
			$serverError = json_decode($e->getMessage());
			$serverErrorArray = json_decode($e->getMessage(), true);
	
			if(!isset($serverErrorArray['errors'])){
				return true;
			}
	
			if(is_object($serverError->errors)){
				$errors = json_decode($e->getMessage(), true);
				$this->fillForm($errors['errors'], $form, $context);
			}else{
				$form->addError(new FormError($translator->trans('%%error%%', array('%%error%%' => $serverError->errors))));
			}
		} else {
			//llega un string de error proveniente de la libreria
			throw new \Exception($e);
		}
	}
	

	protected function isJson($string)
	{
		return (is_string($string) && (
				is_object(json_decode($string)) || is_array(json_decode($string))
		)
		);
	}
	
	protected function fillForm($errors, $form, $context)
	{
		throw new MethodNotImplementedException('fillForm');
	}
	
}