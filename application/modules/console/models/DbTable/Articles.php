<?php

class Console_Model_DbTable_Articles extends Zend_Db_Table_Abstract
{

    protected $_name = 'articles';

    public function getArticles($issue){
	$result = $this->fetchAll('issue = '.$issue,'page_count');
	return $result->toArray();
    }

    public function getArticle($id){
	$result = $this->fetchRow('id = '.$id);
	return $result->toArray();
    }

    public function updateArticle($data,$id){
	$result = $this->update($data,'id = '.$id);
	return $result;
    }

    public function insertArticle($data){
	$result = $this->insert($data);
	return $result;
    }

    public function deleteArticle($id){
	$result = $this->delete('id = '.$id);
	return $result;
    }
}

