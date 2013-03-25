<?php

class WspolpracaController extends Zend_Controller_Action
{

    private $coopArr;
    private $coopLetters;
    
    public function init()
    {
        $coopObj = new Application_Model_DbTable_Cooperants();
	$this->coopArr = $coopObj->getActive();
	$tmp=array();
	foreach ($this->coopArr as $coop){
	    $tmp[]=strtoupper(substr($coop['name'],0,1));
	}
	$this->coopLetters=array_unique($tmp);
	sort($this->coopLetters);
    }

    public function indexAction()
    {
	$this->init();
//        $coopObj = new Application_Model_DbTable_Cooperants();
//	$coopArr = $coopObj->getActive();
	$this->view->coopArr = $this->coopArr;
	$this->view->coopLetters = $this->coopLetters;
    }

    public function filterAction()
    {
	$this->init();
	$coopObj = new Application_Model_DbTable_Cooperants();
	$coopArr = $coopObj->getFiltered($this->getRequest()->getParam('letter'));
	$this->view->coopArr = $coopArr;
	$this->view->coopLetters = $this->coopLetters;
    }


}



