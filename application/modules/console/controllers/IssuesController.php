<?php

class Console_IssuesController extends Zend_Controller_Action
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
        $issuesObj = new Console_Model_DbTable_Issues();
	$issuesArr = $issuesObj->getAll();
	$this->view->issues = $issuesArr;
    }

    public function archiveAction()
    {
        $id = $this->getRequest()->getParam('id');
	$archive = $this->getRequest()->getParam('archive');
	$issueObj = new Console_Model_DbTable_Issues();

	if ($archive == 0)
	    $issueObj->dearchive();

	$data = array('archive' => $archive);
	$issueObj->updateIssue($data,$id);
	header('Location:/console/issues/');
    }

    public function activateAction()
    {
	$id = $this->getRequest()->getParam('id');
	$active = $this->getRequest()->getParam('active');
	$issueObj = new Console_Model_DbTable_Issues();

	$data = array('active' => $active);
	$issueObj->updateIssue($data,$id);

	header('Location:/console/issues/');
    }


}





