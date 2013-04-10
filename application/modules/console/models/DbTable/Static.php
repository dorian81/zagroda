<?php

class Console_Model_DbTable_Static extends Zend_Db_Table_Abstract
{

    protected $_name = 'static';

    public function getAll(){
	$result = $this->fetchAll();
	return $result->toArray();
    }

}

