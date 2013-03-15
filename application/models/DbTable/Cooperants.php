<?php

class Application_Model_DbTable_Cooperants extends Zend_Db_Table_Abstract
{

    protected $_name = 'cooperants';

    public function getLeftBlock(){
	$row = $this->fetchAll('active=1 AND block=1');
	return $row->toArray();
    }

}

