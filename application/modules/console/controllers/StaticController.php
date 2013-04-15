<?php

class Console_StaticController extends Zend_Controller_Action
{

    private $form = null;

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
	
	$name = $form->createElement('text','name');
	$title = $form->createElement('text','title');
	$keywords = $form->createElement('textarea','keywords',array('cols' => '50','rows' => '2'));
	$content = $form->createElement('textarea','content');
	$active = $form->createElement('radio','active');
	$menu = $form->createElement('radio','menu');
	$submit = $form->createElement('submit','Zapisz');

	$decorator = array(
			   'ViewHelper',
			   'Description',
			   'Errors',
			   array(array('data'=>'HtmlTag'), array('tag' => 'td')),
			   array('Label', array('tag' => 'td')),
			   array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
			  );

	$name->setRequired(true)
	     ->setLabel('Nazwa')
	     ->setDecorators($decorator);
	$title->setLabel('Tytuł')
	      ->setDecorators($decorator);
	$active->setLabel('Aktywny')
	       ->addMultiOptions(array(
		    '1' => 'Tak',
		    '0' => 'Nie'
		))
		->setSeparator('')
		->setValue('2')
		->setDecorators($decorator);
	$menu ->setLabel('Pokazuj w menu')
	       ->addMultiOptions(array(
		    '1' => 'Tak',
		    '0' => 'Nie'
		))
		->setSeparator('')
		->setValue('2')
		->setDecorators($decorator);
	$keywords->setLabel('Słowa kluczowe')
		 ->setDecorators($decorator);
	$content->setRequired(true)
		->setLabel('Treść')
		->setAttrib('id','editor1')
		->setDecorators($decorator);
	$submit->setDecorators(array(
			'ViewHelper',
			'Description',
			'Errors', 
			array(
			    array('data'=>'HtmlTag'), 
			    array('tag' => 'td','colspan'=>'2','align'=>'center')
			    ),
			array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
			));
	$form->addElements(array(
				 $name,
				 $title,
				 $active,
				 $menu,
				 $keywords,
				 $content,
				 $submit
				)
			   );
	$form->setDecorators(array(
				   'FormElements',
				   array(array('data'=>'HtmlTag'),array('tag'=>'table')),
				   'Form'
				  )
			    );
	$this->form = $form;
    }

    public function editAction()
    {
	$save = $this->getRequest()->getParam('save');
	$id = $this->getRequest()->getParam('id');

	$staticObj = new Console_Model_DbTable_Static();

	if (isset($save) && $save == 1){
	    $data = array(
			  'name' => $this->getRequest()->getPost('name'),
			  'title' => $this->getRequest()->getPost('title'),
			  'keywords' => $this->getRequest()->getPost('keywords'),
			  'content' => $this->getRequest()->getPost('content'),
			  'active' => $this->getRequest()->getPost('active'),
			  'menu' => $this->getRequest()->getPost('menu'),
			 );

	    $staticObj->updateStatic($data,$id);
	    header('Location: /console/index/list-static');
	}else{
	    $staticArr = $staticObj->getStatic($id);

	    $form = $this->form;
	    $form->getElement('name')->setValue($staticArr['name']);
	    $form->getElement('title')->setValue($staticArr['title']);
	    $form->getElement('active')->setValue($staticArr['active']);
	    $form->getElement('menu')->setValue($staticArr['menu']);
	    $form->getElement('keywords')->setValue($staticArr['keywords']);
	    $form->getElement('content')->setValue($staticArr['content']);
	    $form->setAction('/console/static/edit?save=1&id='.$id)
		 ->setMethod('POST');

	    $this->view->form = $form;
	}
    }

    public function newAction()
    {
	$save = $this->getRequest()->getParam('save');
	if (isset($save) && $save == 1){
	    $staticObj = new Console_Model_DbTable_Static();
	    $ordinalNoArr = $staticObj->getMaxOrdinalNo();
	    $ordinalNo = $ordinalNoArr['maxON']+1;
	    $data = array(
			  'name' => $this->getRequest()->getPost('name'),
			  'title' => $this->getRequest()->getPost('title'),
			  'keywords' => $this->getRequest()->getPost('keywords'),
			  'content' => $this->getRequest()->getPost('content'),
			  'active' => $this->getRequest()->getPost('active'),
			  'menu' => $this->getRequest()->getPost('menu'),
			  'ordinal_no' => $ordinalNo
			 );
	    $staticObj->insertNew($data);
	    header('Location: /console/index/list-static');
	}else{
	    $this->init();
	    $this->form->setAction('/console/static/new?save=1')
		       ->setMethod('POST');
	    $this->view->form = $this->form;
	}
    }

    public function activateAction()
    {
	$id = $this->getRequest()->getParam('id');
	$active = $this->getRequest()->getParam('active');
	$data = array('active' => $active);
	$staticObj = new Console_Model_DbTable_Static();
	$staticObj->updateStatic($data,$id);
	header('Location: /console/index/list-static/');
    }

    public function upAction()
    {
        $staticObj = new Console_Model_DbTable_Static();
	$staticObj->up($this->getRequest()->getParam('id'), $this->getRequest()->getParam('ord'));
	header('Location: /console/index/list-static/');
    }

    public function downAction()
    {
        $staticObj = new Console_Model_DbTable_Static();
	$staticObj->down($this->getRequest()->getParam('id'), $this->getRequest()->getParam('ord'));
	header('Location: /console/index/list-static/');
    }

    public function deleteAction()
    {
        $staticObj = new Console_Model_DbTable_Static();
	$staticObj->del($this->getRequest()->getParam('id'));
	header('Location: /console/index/list-static/');
    }


}















