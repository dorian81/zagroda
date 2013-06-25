<?php

class Application_Model_DbTable_Cooperants extends Zend_Db_Table_Abstract
{

    protected $_name = 'cooperants';

    public function getLeftBlock(){
	$result = $this->fetchAll('active=1 AND block=1');
	return $result->toArray();
    }
    public function getActive(){
	$result = $this->fetchAll('active=1');
	return $result->toArray();
    }
    public function getFiltered($letter){
        $result = $this->fetchAll('UCASE(SUBSTR(name,1,1))="'.$letter.'" AND active=1');
        return $result->toArray();
    }
}

