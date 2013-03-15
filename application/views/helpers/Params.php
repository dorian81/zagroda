<?php

class Zend_View_Helper_Params extends Zend_View_Helper_Abstract{

    protected $params;

    public function params($name){
	$paramsObj = new Application_Model_DbTable_Parameters();
	$params = $paramsObj->getParams();
	return $params[$name];
    }

}