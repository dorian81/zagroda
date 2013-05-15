<?php

class Console_Model_DbTable_Issues extends Zend_Db_Table_Abstract
{

    protected $_name = 'issues';

    public function getAll(){
	$result = $this->fetchAll('1','ordinal_no DESC');
	return $result->toArray();
    }

    public function dearchive(){
	$data = array ('archive' => 1);
	$result = $this->update($data,'archive = 0');
	return $result;
    }

    public function updateIssue($data,$id){
	$result = $this->update($data,'id = '.$id);
	return $result;
    }

    public function getIssue($id){
	$result = $this->fetchRow('ordinal_no = '.$id);
	return $result->toArray();
    }

    public function insertIssue($data){
	$result = $this->insert($data);
	return $result;
    }
}

