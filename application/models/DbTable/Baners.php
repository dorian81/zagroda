<?php

class Application_Model_DbTable_Baners extends Zend_Db_Table_Abstract
{

    protected $_name = 'baners';

    public function getLeftBaners(){
	$row=$this->fetchAll('position="left" AND active = 1 AND date_from <= NOW() AND date_to >= NOW()');
	return $row->toArray();
    }

    public function getTopBaner(){
	$row = $this->fetchRow('position = "top" AND active = 1 AND date_from <= NOW() AND date_to >= NOW()');
	if ($row) 
	    return $row->toArray();
	else
	    return $row;
    }
}

