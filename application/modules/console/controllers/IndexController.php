<?php

class Console_IndexController extends Zend_Controller_Action
{

    private $session = null;

    public function init()
    {
	$this->session = new Zend_Session_Namespace('index');
	if (!isset($this->session->adminId)){
	    header('Location: /console/login');
	}else{
	    $adminArr['imie'] = $this->session->name;
	    $adminArr['nazwisko'] = $this->session->surname;
	    $this->view->admin = $adminArr;
	}
    }

    public function indexAction()
    {
    }

    public function listIssuesAction()
    {
        $issuesObj = new Console_Model_DbTable_Issues();
	$issuesArr = $issuesObj->getAll();
	$this->view->issues = $issuesArr;
    }

    public function logoutAction()
    {
	Zend_Session::namespaceUnset('index');
	header('Location:/console/');
    }
}

















