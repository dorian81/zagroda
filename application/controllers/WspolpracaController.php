<?php

class WspolpracaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $coopObj = new Application_Model_DbTable_Cooperants();
	$coopArr = $coopObj->getActive();
	$this->view->coopArr = $coopArr;
    }

    public function filterAction()
    {
        // action body
    }


}



