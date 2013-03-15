<?php

class Application_Model_DbTable_Issues extends Zend_Db_Table_Abstract
{

    protected $_name = 'issues';

    public function getNewestIssue()
    {
	$row=$this->fetchRow('active = 1 AND archive=0');
	if (!$row){
	    throw new Exception("Nie odnaleziono najnowszego numeru");
	}
	return $row->toArray();
    }

    public function getAll(){
	$row = $this->fetchAll();
	return $row->toArray();
    }

}

