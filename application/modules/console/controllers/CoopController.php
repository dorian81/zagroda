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
	$file = new Zend_Form_Element_File('img');
	$file->setLabel('Logo')
	     ->setAttrib('size','50')
	     ->setRequired(true)
	     ->setDecorators( array(
		'File',
		'Errors',
		array(array('data'=>'HtmlTag'), array('tag' => 'td')),
		array('Label', array('tag' => 'td')),
		array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
	       ));
	//     ->setDestination('/banery');

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
	    header('Location: /console/index/coop');
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

	if (isset($save) && $save==1){
	    $upload = new Zend_File_Transfer_Adapter_Http();
	    $upload->setDestination(APPLICATION_PATH.'../banery');
	    $upload->recieve();
	    $this->view->file = $upload->getFileInfo();
	}else{
	    $form = $this->form;
	    $form->getElement('img')->setDestination(APPLICATION_PATH.'/banery');
	    
	    $this->view->form = $form;
	}
    }


}





