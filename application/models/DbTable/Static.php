<?php

class Application_Model_DbTable_Static extends Zend_Db_Table_Abstract
{

    protected $_name = 'static';

    public function getStatic($id)
    {
	$id = (int)$id;
	$row = $this->fetchRow('id = ' . $id);
	if (!$row) {
	    throw new Exception("Nie znaleziono strony!");
	}
	return $row->toArray();
    }

    public function getStaticMainPage()
    {
	$row = $this->fetchRow('id=2');
	if (!$row){
	    throw new Exception("Nie znaleziono strony głównej");
	}
	return $row;
    }
    public function getMenuStatic(){
	$result = $this->fetchAll('menu=1 AND id<>1');
	if (!$result){
	    throw new Exception("Brak linków do menu");
	}
	return $result->toArray();
    }

    public function getAll(){
	$result = $this->fetchAll();
	return $result->toArray();
    }

}

