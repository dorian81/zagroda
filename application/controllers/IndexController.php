<?php

class IndexController extends Zend_Controller_Action
{

    private $staticObj = null;

    private $newestIssue = null;

    private $newestNews = null;

    private $leftBaners = null;

    private $leftCoop = null;

    public function init()
    {
		$this->staticObj = new Application_Model_DbTable_Static();
		$this->newestIssue = new Application_Model_DbTable_Issues();
		$this->newestNews = new Application_Model_DbTable_News();
		$this->leftBaners = new Application_Model_DbTable_Baners();
		$this->leftCoop = new Application_Model_DbTable_Cooperants();
    }

    public function indexAction()
    {
        /* Initialize action controller here */
		
		$this->init();
		
		//treść statyczna strony głównej
		$this->static = $this->staticObj->getStatic('1');
		$this->view->staticContent = $this->static;
		
		//Dane najnowszego numeru
		$newestIssueArray = $this->newestIssue->getNewestIssue();

		//Zajawki najnowszych artykułów
		$this->view->news = $this->newestNews->getNewest($newestIssueArray['ordinal_no']);
		
		//Okładka najnowszego numeru
		$this->view->coverImg = 'covers/'.$newestIssueArray['cover'].'_2.jpg';
		
		//Numer porządkowy najnowszego numer
		$this->view->newestNo = $newestIssueArray['ordinal_no'];

		//Banery
		
		$this->view->baners = $this->leftBaners->getLeftBaners();

		//Bloczek współpracy
		
		$this->view->coop = $this->leftCoop->getLeftBlock();
    }

    public function staticAction()
    {
        $this->init();
		
		//Dane najnowszego numeru
		$newestIssueArray = $this->newestIssue->getNewestIssue();
		
		//Okładka najnowszego numeru
		$this->view->coverImg = 'covers/'.$newestIssueArray['cover'].'_2.jpg';

		//Banery
		
		$this->view->baners = $this->leftBaners->getLeftBaners();

		//Bloczek współpracy
		
		$this->view->coop = $this->leftCoop->getLeftBlock();
		
		//zawartość strony		
		$static = $this->staticObj->getStatic($this->getRequest()->getParam('id'));
		$this->view->staticContent = $static;
    }
}









