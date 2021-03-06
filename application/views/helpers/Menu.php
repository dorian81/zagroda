<?php

class Zend_View_Helper_Menu extends Zend_View_Helper_Abstract{


    protected $menu='';

    public function menu(){
	$menu='<a href="/">Strona główna</a>';
	
	//pobranie parametrów aplikacji
	$paramsObj = new Application_Model_DbTable_Parameters();
	$params = $paramsObj->getParams();

	//dodanie "Współpracy"
	if ($params['cooper_enabled']=='true'){
	    $menu.='<a href="/wspolpraca">'.$params['cooper_name'].'</a>';
	}
	
	//pobranie stron statycznych biorących udział w menu
	$staticObj= new Application_Model_DbTable_Static();
	$staticArr=$staticObj->getMenuStatic();
	foreach ($staticArr as $static){
	    $menu.='<a href="/index/static?id='.$static['id'].'">'.$static['name'].'</a>';
	}
	
	//dodanie pozostałych elementów menu
	$issueObj = new Application_Model_DbTable_Issues();
	$newest = $issueObj->getNewestIssue();
	$menu.='<a href="/issue/show?id='.$newest['ordinal_no'].'">'.$params['newest_name'].'</a><a href="/issue">'.$params['archive_name'].'</a>';

	return $menu;
    }
}