<?php

class Console_Model_DbTable_Cooperants extends Zend_Db_Table_Abstract
{

    protected $_name = 'cooperants';

    public function getAll(){
	$result = $this->fetchAll();
	return $result->toArray();
    }

    public function getActive($state){
	$result = $this->fetchAll('active = '.$state);
	return $result->toArray();
    }

    public function getBlock($state){
	$result = $this->fetchAll('block = '.$state);
	return $result->toArray();
    }

    public function getActiveBlock($act, $bl){
	$result = $this->fetchAll('active = '.$act.' AND block = '.$bl);
	return $result->toArray();
    }

    public function getLetter($letter){
	$result = $this->fetchAll('UCASE(SUBSTR(name,1,1))="'.$letter.'"');
	return $result->toArray();
    }

    public function getCoop($id){
	$result = $this->fetchRow('id = '.$id);
	return $result->toArray();
    }

    public function updateCoop($data,$id){
	$result = $this->update($data,'id = '.$id);
	return $result;
    }

    public function insertCoop($data){
	$result = $this->insert($data);
	return $result;
    }
}

