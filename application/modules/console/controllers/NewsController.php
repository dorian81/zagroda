<?php

class Console_NewsController extends Zend_Controller_Action
{

    private $form;

    public function init()
    {
        $this->session = new Zend_Session_Namespace('index');
	if (!isset($this->session->adminId)){
	    header('Location: /console/login');
	}else{
	    $adminArr['imie'] = $this->session->name;
	    $adminArr['nazwisko'] = $this->session->surname;
	    $this->view->admin = $adminArr;
	}

	$form = new Zend_Form();

	$h3 = $form->createElement('text','h3');
	$h1 = $form->createElement('text','h1');
	$content = $form->createElement('textarea','content');
	$page = $form->createElement('select','page');
	$submit = $form->createElement('submit','Zapisz');

	$decorator = array(
		    'ViewHelper',
		    'Description',
		    'Errors',
		    array(array('data'=>'HtmlTag'), array('tag' => 'td')),
		    array('Label', array('tag' => 'td')),
		    array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
		);

	$h3->setLabel('Tytuł (H3)')
	   ->setDecorators($decorator);
	$h1->setLabel('Tytuł (H1)')
	   ->setDecorators($decorator);
	$content->setLabel('Treść')
		->setAttrib('id','edit1')
		->setDecorators($decorator);
	$page->setLabel('Strona')
	     ->setDecorators($decorator);
	$submit->setdecorators(array(
		    'ViewHelper',
		    'Description',
		    'Errors',
		    array(
			array('data'=>'HtmlTag'), 
			array('tag' => 'td', 'colspan' => 2)),
		    array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
		));

	$form->addElements(array($h3,$h1,$content,$page,$submit));
	$form->setDecorators(array(
		    'FormElements',
		    array(array('data'=>'HtmlTag'),array('tag'=>'table')),
		    'Form'
		));

	$this->form = $form;
    }

    public function indexAction()
    {
	$issue = $this->getRequest()->getParam('issue');
	$newsObj = new Console_Model_DbTable_News();

	$news = $newsObj->getNews($issue);
	$this->view->news = $news;
	$this->view->issue = $issue;
	$this->view->form =$this->form;
    }


}

