<?php

class IssueController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
	$archivalObj = new Application_Model_DbTable_Issues();
	$archivalArr = $archivalObj->getArchival();
	$this->view->archivalArr = $archivalArr;
    }

    public function showAction()
    {
        $issueObj = new Application_Model_DbTable_Issues();
	$issueArr = $issueObj->getIssue($this->getRequest()->getParam('id'));
	$this->view->issue = $issueArr;

	$articlesObj = new Application_Model_DbTable_Articles();
	$articlesArr = $articlesObj->getArticles($this->getrequest()->getParam('id'));
	$this->view->articles = $articlesArr;
    }


}



