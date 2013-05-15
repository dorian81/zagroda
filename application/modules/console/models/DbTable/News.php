<?php

class Console_Model_DbTable_News extends Zend_Db_Table_Abstract
{

    protected $_name = 'news';

    public function getNews($issue){
	$result = $this->fetchAll('issue = '.$issue,'page');
	return $result->toArray();
    }

    public function getSingleNews($id){
	$result = $this->fetchRow('id = '.$id);
	return $result->toArray();
    }

    public function updateNews($data,$id){
	$result = $this->update($data,'id = '.$id);
	return $result;
    }

    public function insertNews($data){
	$result = $this->insert($data);
	return $result;
    }

    public function deleteNews($id){
	$result = $this->delete('id = '.$id);
	return $result;
    }
}

