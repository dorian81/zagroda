<?php

class Console_ParamsController extends Zend_Controller_Action
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

	$file = $form->createElement('file','file');
	$file->setDestination('/var/www/zagroda/public/img')
	     ->setLabel('Plik')
	     ->setDecorators(array(
		'File',
		'Errors',
		array(array('data'=>'HtmlTag'), array('tag' => 'td')),
		array('Label', array('tag' => 'span')),
	     ))
	     ->addValidator('MimeType', false, array('image/png', 'image/jpeg'))
	     ->addValidator('ImageSize', false,
                      array('minwidth' => 1072,
                            'maxwidth' => 1072,
                            'minheight' => 130,
                            'maxheight' => 135)
                      );

	$submit = $form->createElement('submit','Dodaj');
	$submit->setDecorators(array(
		 'ViewHelper',
		 'Description',
		 'Errors',
		 array(array('data'=>'HtmlTag'), array('tag' => 'span')),
		));

	$form->addElements(array($file, $submit))
             ->setAction('/console/params/bg/?save=1')
             ->setMethod('Post');
	$form->setDecorators(array(
	      'FormElements',
	      array(array('data'=>'HtmlTag'),array('tag'=>'div')),
	      'Form'
	     ));
	$this->form = $form; 
    }

    public function saveAction()
    {
        $paramsObj = new Console_Model_DbTable_Parameters();
	$data = array(
		'news_name' => $this->getRequest()->getPost('news_name'),
		'news_enabled' => $this->getRequest()->getPost('news_enabled'),
		'cooper_name' => $this->getRequest()->getPost('cooper_name'),
		'cooper_enabled' => $this->getRequest()->getPost('cooper_enabled'),
		'newest_name' => $this->getRequest()->getPost('newest_name'),
		'archive_name' => $this->getRequest()->getPost('archive_name'),
		'header_title' => $this->getRequest()->getPost('header_title'),
		'cooper_block_enabled' => $this->getRequest()->getPost('cooper_block_enabled'),
		'baners_enabled' => $this->getRequest()->getPost('baners_enabled'),
		'baners_left_block_title_enabled' => $this->getRequest()->getPost('baners_left_block_title_enabled'),
		'baners_left_block_name' => $this->getRequest()->getPost('baners_left_block_name'),
		'no_new_issue' => $this->getRequest()->getPost('no_new_issue'));
	$this->view->params = $data;
	$paramsObj->updateParams($data);
	header('Location: /console/params/');
    }

    public function bgAction()
    {
        $save = $this->getRequest()->getParam('save');

	$paramObj = new Console_Model_DbTable_Parameters();
	$bg = $paramObj->getBg();

	if (isset($save) && $save == 1 && $this->form->isValid($_POST)){
	    $file = $this->form->getValue('file');
	    if ($paramObj->updateBg($file)){
		unlink('/var/www/zagroda/public/img/'.$bg);
	    }
	    header('Location: /console/params/');
	}else{
	    $this->view->bg = $bg;
	    $this->view->form = $this->form;
	}
    }

    public function indexAction()
    {
	$paramsObj = new Console_Model_DbTable_Parameters();
	$paramsArr = $paramsObj->getAll();
	$tmp = array();
	foreach($paramsArr as $value){
	    $tmp[$value['name']] = $value['value'];
	}
	$paramsArr = $tmp;
	$this->view->params = $paramsArr;
    }
}





