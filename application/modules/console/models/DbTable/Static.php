<?php

class Console_Model_DbTable_Static extends Zend_Db_Table_Abstract
{

    protected $_name = 'static';

    public function getAll(){
	$result = $this->fetchAll(null,'ordinal_no');
	return $result->toArray();
    }

    public function getMaxOrdinalNo(){
	$result = $this->fetchRow($this->select()->from($this,array(new Zend_Db_Expr('max(ordinal_no) AS maxON'))));
	return $result->toArray();
    }

    public function insertNew($data){
	$result = $this->insert($data);
	return $result;
    }

    public function getStatic($id){
	$result = $this->fetchRow('id = '.$id);
	return $result->toArray();
    }

    public function updateStatic($data,$id){
	$result = $this->update($data, 'id = '.$id);
	return $result;
    }

    public function up($id, $order){
	$data = array('ordinal_no' => $order);
	$result = $this->update($data,'ordinal_no = '.($order-1));
	$data['ordinal_no'] = $order-1;
	$result = $this->update($data, 'id = '.$id);
	return $result;
    }

    public function down($id, $order){
	$data = array('ordinal_no' => $order);
	$result = $this->update($data,'ordinal_no = '.($order+1));
	$data['ordinal_no'] = $order+1;
	$result = $this->update($data, 'id = '.$id);
	return $result;
    }

    public function del($id){
	$result = $this->delete('id = '.$id);
	return $result;
    }
}

