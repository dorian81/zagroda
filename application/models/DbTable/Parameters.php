<?php

class Application_Model_DbTable_Parameters extends Zend_Db_Table_Abstract
{

    protected $_name = 'parameters';
    
    public function getParams(){
	$row = $this->fetchAll();
	$result = array();
	foreach ($row as $data){
	    $result[$data['name']]=$data['value'];
	}
	return $result;
    }

}

