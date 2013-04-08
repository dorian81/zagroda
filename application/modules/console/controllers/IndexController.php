<?php

class Console_IndexController extends Zend_Controller_Action
{

    public function init()
    {

    }

    public function indexAction()
    {
	$session = new Zend_Session_Namespace('index');
	if (!isset($session->login)){
	    header('Location: /console/login');
	}else $this->view->login='blabla';
    }


}

