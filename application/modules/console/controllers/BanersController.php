<?php

class Console_BanersController extends Zend_Controller_Action
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
	$position = $form->createElement('radio','position');
	$type = $form->createElement('radio','type');
	$img1 = $form->createElement('file','img1');
	$swf1 = $form->createElement('file','swf1');
	$html = $form->createElement('textarea','html');
	$url = $form->createElement('text','url');
	$active = $form->createElement('radio','active');
	$dateFrom = $form->createElement('text','date_from');
	$dateTo = $form->createElement('text','date_to');
	$submit = $form->createElement('submit','Zapisz');

	$decorator = array(
	       'ViewHelper',
	       'Description',
	       'Errors',
	       array(array('data'=>'HtmlTag'), array('tag' => 'td')),
	       array('Label', array('tag' => 'td')),
	       array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
	      );

	$fileDecorator = array(
			 'File',
			 'Errors',
			 array(array('data'=>'HtmlTag'), array('tag' => 'td')),
			 array('Label', array('tag' => 'td')),
			 array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
			);

	$name->setLabel('Nazwa')
	     ->setDecorators($decorator);
	$position->setLabel('Położenie')
		 ->addMultiOptions(array(
		    'left' => 'Lewy',
		    'top' => 'Górny'
		 ))
		 ->setSeparator('')
		 ->setDecorators($decorator);
	$type->setLabel('Typ')
	     ->addMultiOptions(array(
		'img' => 'Obrazek',
		'swf' => 'Flash',
		'html' => 'HTML'
	     ))
	     ->setSeparator('')
	     ->setValue('img')
	     ->setAttrib('onclick','javascript:type_change()')
	     ->setDecorators($decorator);
	$img1->setLabel('Baner graficzny')
	     ->setDecorators($fileDecorator);
	$swf1->setLabel('Baner flash')
	     ->setDecorators($fileDecorator);
	$html->setlabel('Baner HTML')
	     ->setDecorators($decorator);
	$url->setLabel('Url')
	    ->setDecorators($decorator);
	$active->setlabel('Aktywny')
	       ->addMultiOptions(array(
		  '1' => 'Tak',
		  '0' => 'Nie'
	       ))
	       ->setSeparator('')
	       ->setValue('0')
	       ->setDecorators($decorator);
	$dateFrom->setLabel('Pokazuj od')
		 ->setDecorators($decorator);
	$dateTo->setLabel('Pokazuj do')
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
	    $position,
	    $type,
	    $img1,
	    $swf1,
	    $html,
	    $url,
	    $active,
	    $dateFrom,
	    $dateTo,
	    $submit));

	$form->setDecorators(array(
		   'FormElements',
		   array(array('data'=>'HtmlTag'),array('tag'=>'table')),
		   'Form'
		  ));

	$this->form = $form;
    }

    public function indexAction()
    {
        $banersObj = new Console_Model_DbTable_Baners();
	$banersArr = $banersObj->getAll();
	$this->view->baners = $banersArr;
    }

    public function newAction()
    {
	$this->form->setAction('/console/baners/save/');
        $this->view->form = $this->form;
    }

    public function saveAction()
    {
        $form['name'] = $this->getRequest()->getPost('name');
	$form['position'] = $this->getRequest()->getPost('position');
	$form['type'] = $this->getRequest()->getPost('type');
	$img = $this->getRequest()->getPost('img');
	$form['img'] = (isset($img))?$this->getRequest()->getPost('img'):'';
	
    }


}





