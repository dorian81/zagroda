<?php

class Application_Model_DbTable_Articles extends Zend_Db_Table_Abstract
{

    protected $_name = 'articles';

    public function getArticles($issue){
	$result = $this->fetchAll('issue = '.$issue,'page_count');
	return $result->toArray();
    }
}

