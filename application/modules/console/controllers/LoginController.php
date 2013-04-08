<?php

class Console_LoginController extends Zend_Controller_Action
{
    public function indexAction()
    {
	$form = new Zend_Form;
	$form->setAction('login')->setMethod('POST');

	$login=$form->createElement('text','lorin');
	$pass=$form->createElement('password','pass');
	$login->addValidator('alnum')
	      ->setRequired(true)
	      ->addFilter('StringToLower')
	      ->setLabel('Login');

	$pass->setRequired(true)
	     ->setLabel('Hasło');

	$form->addElement($login);
	$form->addElement($pass);
	$form->addElement('submit','Zaloguj');
        $this->view->form = $form;
    }
}

