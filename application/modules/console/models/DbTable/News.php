<?php

class Console_Model_DbTable_News extends Zend_Db_Table_Abstract
{

    protected $_name = 'news';

    public function getNews($issue){
	$result = $this->fetchAll('issue = '.$issue);
	return $result->toArray();
    }
}

