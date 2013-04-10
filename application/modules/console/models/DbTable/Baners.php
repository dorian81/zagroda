<?php

class Console_Model_DbTable_Baners extends Zend_Db_Table_Abstract
{

    protected $_name = 'baners';

    public function getAll(){
	$result = $this->fetchAll();
	return $result->toArray();
    }
}

