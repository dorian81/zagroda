<?php

class Console_UploadController extends Zend_Controller_Action
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

	$form = new Zend_Form;

	$file = $form->createElement('file','file');
	$file->setDestination('/var/www/zagroda/public/upload')
	     ->setLabel('Plik')
	     ->setDecorators(array(
		'File',
		'Errors',
		array(array('data'=>'HtmlTag'), array('tag' => 'td')),
		array('Label', array('tag' => 'span')),
        	)); 

	$submit = $form->createElement('submit','Dodaj');
	$submit->setDecorators(array(
		'ViewHelper',
		'Description',
		'Errors',
		array(array('data'=>'HtmlTag'), array('tag' => 'span')),
        	));

	$form->addElements(array($file, $submit))
	     ->setAction('/console/upload/upload')
	     ->setMethod('Post');
	$form->setDecorators(array(
	   'FormElements',
	   array(array('data'=>'HtmlTag'),array('tag'=>'div')),
	   'Form'
	  )
            );
	$this->form = $form;
    }

    public function indexAction()
    {
        $dir = new DirectoryIterator('/var/www/zagroda/public/upload');
	$files = array();

	foreach ($dir as $file){
	    if (!$file->isDot()){
		$files[] = $file->__toString();
	    }
	}
	$this->view->files = $files;
	$this->view->form = $this->form;
    }

    public function uploadAction()
    {
        $file = $this->form->getValue('file');
	header('Location: /console/upload/');
    }

    public function delAction()
    {
        unlink('/var/www/zagroda/public/upload/'.$this->getRequest()->getParam('file'));
	header('Location: /console/upload/');
    }


}





