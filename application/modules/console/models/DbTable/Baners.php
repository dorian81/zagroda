<?php

class Console_Model_DbTable_Baners extends Zend_Db_Table_Abstract
{

    protected $_name = 'baners';

    public function getAll(){
	$result = $this->fetchAll();
	return $result->toArray();
    }

    public function insertNew($data){
	$result = $this->insert($data);
	return $result;
    }

    public function updateBaner($data,$id){
	$result = $this->update($data,'id = '.$id);
	return $result;
    }

    public function getBaner($id){
	$result = $this->fetchRow('id = '.$id);
	return $result->toArray();
    }

    public function deleteBaner($id){
	$result = $this->delete('id = '.$id);
	return $result;
    }
}

