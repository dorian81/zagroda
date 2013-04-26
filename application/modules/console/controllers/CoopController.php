<?php

class Console_CoopController extends Zend_Controller_Action
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
	$url = $form->createElement('text','url');
	$file = $form->createElement('file','img');
	$active = $form->createElement('radio','active');
	$block = $form->createElement('radio','block');
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
	     ->setAttrib('size','50')
	     ->setDecorators($decorator);
	$url->setRequired(true)
	    ->setLabel('Link')
	    ->setAttrib('size','50')
	    ->setDecorators($decorator);
	$active->setLabel('Aktywny')
	       ->addMultiOptions(array(
		'1' => 'Tak',
		'0' => 'Nie'
	       ))
	       ->setSeparator('')
	       ->setValue('0')
	       ->setDecorators($decorator);
	$block->setLabel('Pokazuj w bloczku')
	       ->addMultiOptions(array(
		'1' => 'Tak',
		'0' => 'Nie'
	       ))
	       ->setSeparator('')
	       ->setValue('0')
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
	$file->setLabel('Logo')
	     ->setAttrib('size','50')
	     ->setRequired(true)
	     ->setDecorators( array(
		'File',
		'Errors',
		array(array('data'=>'HtmlTag'), array('tag' => 'td')),
		array('Label', array('tag' => 'td')),
		array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
	       ))
	     ->setDestination('/var/www/zagroda/public/banery');

	$form->setAttrib('enctype', 'multipart/form-data');
	$form->addElements(array(
		 $name,
		 $url,
		 $file,
		 $active,
		 $block,
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

	$coopObj = new Console_Model_DbTable_Cooperants();

	if (isset($save) && $save = '1'){
	    $data = array(
		'name' => $this->getRequest()->getPost('name'),
		'url' => $this->getRequest()->getPost('url'),
		'active' => $this->getRequest()->getPost('active'),
		'block' => $this->getRequest()->getPost('block')
		);

	    $coopObj->updateCoop($data,$id);
	    header('Location: /console/coop/');
	}else{
	    $coopArr = $coopObj->getcoop($id);

	    $form = $this->form;
	    $form->getElement('name')->setValue($coopArr['name']);
	    $form->getElement('url')->setValue($coopArr['url']);
	    $form->getElement('active')->setValue($coopArr['active']);
	    $form->getElement('block')->setValue($coopArr['block']);
	    $form->setAction('/console/coop/edit?save=1&id='.$id)
		 ->setMethod('POST');
	    $form->getelement('img')->setAttrib('style','display:none');

	    $this->view->form = $form;
	}
    }

    public function newAction()
    {
	$save = $this->getRequest()->getParam('save');

	$coopObj = new Console_Model_DbTable_Cooperants();

	if (isset($save) && $save==1){
	    $this->form->getValues();

	    $data = array(
		'name' => $this->getRequest()->getPost('name'),
		'url' => $this->getRequest()->getPost('url'),
		'active' => $this->getRequest()->getPost('active'),
		'block' => $this->getRequest()->getPost('block'),
		'img' => $this->form->getValue('img')
		);
	    $coopObj->insertCoop($data);
	    header('Location: /console/coop/');
	}else{
	    $form = $this->form;
	    $form->setAction('/console/coop/new?save=1')
		 ->setMethod('POST');
	    $this->view->form = $form;
	}
    }

    public function activateAction()
    {
	$id = $this->getRequest()->getParam('id');
	$active = $this->getRequest()->getParam('active');
	$data = array('active' => $active);
	$staticObj = new Console_Model_DbTable_Cooperants();
	$staticObj->updateCoop($data,$id);
	header('Location: /console/coop/');
    }

    public function blockAction()
    {
	$id = $this->getRequest()->getParam('id');
	$block = $this->getRequest()->getParam('block');
	$data = array('block' => $block);
	$staticObj = new Console_Model_DbTable_Cooperants();
	$staticObj->updateCoop($data,$id);
	header('Location: /console/coop/');
    }

    public function logoAction()
    {
        $id = $this->getRequest()->getParam('id');
	$save = $this->getRequest()->getParam('save');

	$coopObj = new Console_Model_DbTable_Cooperants();
	$coopArr = $coopObj->getCoop($id);

	if (isset($save) && $save = '1'){
	    $img = $this->form->getValue('img');
	    $data = array('img' => $img);
	    if ($coopObj->updateCoop($data,$id)){
		unlink('/var/www/zagroda/public/banery/'.$coopArr['img']);
	    }
	    header('Location: /console/coop/');
	}else{
	    $form = new Zend_Form;
	    $file = $form->createElement('file','img')
			 ->setRequired(true)
			 ->setLabel('Logo')
			 ->setAttrib('size','50');
	    $submit = $form->createElement('submit','Zapisz');
	    $form->addElements(array($file, $submit))
		 ->setAction('/console/coop/logo/?save=1&id='.$id)
		 ->setMethod('Post');
	    $this->form = $form;
	    $this->view->form = $this->form;
	}
    }

    public function indexAction()
    {
	$coopObj = new Console_Model_DbTable_Cooperants();
	$coopArr = $coopObj->getAll();

	foreach ($coopArr as $coop){
	    $tmp[] = strtoupper(substr($coop['name'],0,1));
	}
	$coopLetters = array_unique($tmp);
	sort($coopLetters);
	$this->view->letters = $coopLetters;

	$filter = $this->getRequest()->getParam('filter');
	if ($filter == 1){
	    $block = $this->getRequest()->getPost('block');
	    $active = $this->getRequest()->getPost('active');
	    $letter = $this->getRequest()->getParam('letter');
	    if(isset($block) && $block != 'all' && $active != 'all'){
		$coopArr = $coopObj->getActiveBlock($active, $block);
	    }else if (isset($block) && $block != 'all' && $active == 'all'){
		$coopArr = $coopObj->getBlock($block);
	    }else if (isset($block) && $active != 'all' && $block == 'all'){
		$coopArr = $coopObj->getActive($active);
	    }else if ($letter!=''){
	    $coopArr = $coopObj->getLetter($letter);
	    }
	}
	$this->view->coop = $coopArr;
    }
}













