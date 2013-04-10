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

    public function listStaticAction()
    {
        $staticObj = new Console_Model_DbTable_Static();
	$staticArr = $staticObj->getAll();
	$this->view->static = $staticArr;
	$this->view->count = count($staticArr);
    }

    public function logoutAction()
    {
	Zend_Session::namespaceUnset('index');
	header('Location:/console/');
    }

    public function coopAction()
    {
        $coopObj = new Console_Model_DbTable_Cooperants();
	$coopArr = $coopObj->getAll();

	foreach ($coopArr as $coop){
	    $tmp[] = strtoupper(substr($coop['name'],0,1));
	}
	$coopLetters = array_unique($tmp);
	$this->view->letters = $coopLetters;
	$filter = $this->getRequest()->getParam('filter');
	if ($filter == 1){
	    $block = $this->getRequest()->getPost('block');
	    $active = $this->getRequest()->getPost('active');
	    $letter = $this->getRequest()->getParam('letter');
	    if(isset($block) && $block != 'all' && $active != 'all'){
		$coopArr = $coopObj->getActiveBlock($active, $block);
	    }else if (isset($block) && $block != 'all' && $active == 'all'){
		$coopArr = $coopObj->getBlock($block);
	    }else if (isset($block) && $active != 'all' && $block == 'all'){
		$coopArr = $coopObj->getActive($active);
	    }else if ($letter!=''){
		$coopArr = $coopObj->getLetter($letter);
	    }
	}
	$this->view->coop = $coopArr;
    }

    public function banersAction()
    {
	$banersObj = new Console_Model_DbTable_Baners();
	$banersArr = $banersObj->getAll();
	$this->view->baners = $banersArr;
    }

    public function uploadAction()
    {
	$path = '/upload';
	$dir = glob($path);
	$this->view->content = $dir;
    }

    public function adminsAction()
    {
	$adminsObj = new Console_Model_DbTable_Admins();
	$adminsArr = $adminsObj->getAll();
	$this->view->admins = $adminsArr;
    }

    public function paramsAction()
    {
	$paramsObj = new Console_Model_DbTable_Parameters();
	$paramsArr = $paramsObj->getAll();
	$tmp = array();
	foreach($paramsArr as $value){
	    $tmp[$value['name']] = $value['value'];
	}
	$paramsArr = $tmp;
	$this->view->params = $paramsArr;
    }


}

















