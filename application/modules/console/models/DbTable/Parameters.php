<?php

class Console_Model_DbTable_Parameters extends Zend_Db_Table_Abstract
{

    protected $_name = 'parameters';

    public function getAll(){
	$result = $this->fetchAll();
	return $result->toArray();
    }
}

