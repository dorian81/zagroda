<?php

class Console_Model_DbTable_Admins extends Zend_Db_Table_Abstract
{

    protected $_name = 'admins';

    public function getAll(){
	$result = $this->fetchAll();
	return $result->toArray();
    }

    public function checkLogin($login,$pass){
	$result=$this->fetchRow('login="'.$login.'" AND pass="'.crypt($pass,'zAGROda').'" AND active=1');
	
	if (!$result) {
	    return false;
	}else{
	    return $result->toArray();
	}
    }
}

