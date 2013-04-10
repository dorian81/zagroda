<?php

class Console_LoginController extends Zend_Controller_Action
{


    public function indexAction()
    {
	$form = new Zend_Form;
	$form->setAction('login/login')->setMethod('POST');

	$login=$form->createElement('text','login');
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

	$error = $this->getRequest()->getParam('error');
	if (isset($error)){
	    $this->view->errorMsg = 'Podano nieprawidłowe dane logowania';
	}
	$this->_helper->layout->setLayout('login');
	$this->view->form = $form;
    }

    public function loginAction()
    {
	$adminObj = new Console_Model_DbTable_Admins();
	$adminArr = $adminObj->checkLogin($this->getRequest()->getPost('login'),$this->getRequest()->getPost('pass'));

	if ($adminArr){
	    $session = new Zend_Session_Namespace('index');
	    $session->name = $adminArr['imie'];
	    $session->surname = $adminArr['nazwisko'];
	    $session->adminId = $adminArr['id'];
	    header('Location: /console/');
	}else{
	    header('Location: /console/login/?error=1');
	}
    }
}



