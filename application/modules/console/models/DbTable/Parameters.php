<?php

class Console_Model_DbTable_Parameters extends Zend_Db_Table_Abstract
{

    protected $_name = 'parameters';

    public function getAll(){
	$result = $this->fetchAll();
	return $result->toArray();
    }

    public function updateParams($data){
	$test = true;
	foreach ($data as $key => $row){
	    $tmp = array('value' => $row);
	    $result = $this->update($tmp,'name = "'.$key.'"');
	    if (!$result) $test = $result;
	}
	return $test;
    }

    public function updateBg($bg){
	$data = array('value' => $bg);
	$result = $this->update($data, 'name = "logo_bg"');
	return $result;
    }

    public function getBg(){
	$result = $this->fetchRow('name = "logo_bg"');
	$row = $result->toArray();
	return $row['value'];
    }
}

