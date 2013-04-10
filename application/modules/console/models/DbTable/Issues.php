<?php

class Console_Model_DbTable_Issues extends Zend_Db_Table_Abstract
{

    protected $_name = 'issues';

    public function getAll(){
	$result = $this->fetchAll();
	return $result->toArray();
    }
}

