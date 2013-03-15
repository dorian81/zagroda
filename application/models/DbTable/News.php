<?php

class Application_Model_DbTable_News extends Zend_Db_Table_Abstract
{

    protected $_name = 'news';

    public function getNewest($id)
    {
	$row = $this->fetchAll('issue='.$id);
	if (!$row){
	    throw new Exception('Nie można odnaleźć rekordów dla numeru '.$id);
	}
	return $row->toArray();
    }


}

