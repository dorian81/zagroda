<?php

class Application_Model_DbTable_Baners extends Zend_Db_Table_Abstract
{

    protected $_name = 'baners';
    public function getLeftBaners(){
	$row=$this->fetchAll('type="left"');
	return $row->toArray();
    }

}

