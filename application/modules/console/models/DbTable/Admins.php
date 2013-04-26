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

    public function updateAdmin($data,$id){
	$result = $this->update($data,'id = '.$id);
	return $result;
    }

    public function selectAdmin($id){
	$result = $this->fetchRow('id = '.$id);
	return $result->toArray();
    }

    public function insertAdmin($data){
	$result = $this->insert($data);
	return $result;
    }

    public function selectMaxId(){
	$result = $this->fetchRow($this->select()->from($this,array(new Zend_Db_Expr('max(id) AS maxId'))));
	$row = $result->toArray();
	return $row['maxId'];
    }
}

