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
	$img1->setDestination('/var/www/zagroda/public/baners/img/')
	     ->setLabel('Baner graficzny')
	     ->setDecorators($fileDecorator);
	$swf1->setLabel('Baner flash')
	     ->setDecorators($fileDecorator)
	     ->setDestination('/var/www/zagroda/public/baners/swf');
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
	
	$banerObj = new Console_Model_DbTable_Baners();

        $data = array('name' => $this->getRequest()->getPost('name'),
		      'position' => $this->getRequest()->getPost('position'),
		      'type' => $this->getRequest()->getPost('type'),
		      'img' => '',
		      'swf' => '',
		      'html' => '',
		      'url' => $this->getRequest()->getPost('url'),
		      'active' => $this->getRequest()->getPost('active'),
		      'date_from' => $this->getRequest()->getPost('date_from'),
		      'date_to' => $this->getRequest()->getPost('date_to'));

	$img = $this->form->getValue('img1');
	if (isset($img)){
	    $data['img'] = $img;
	}

	$swf = $this->form->getValue('swf1');
	if (isset($swf)){
	    $data['swf'] = $swf;
	}

	$html = $this->getRequest()->getPost('html');
	if (isset($html)){
	    $data['html'] = $html;
	}

	$banerObj->insertNew($data);
	header('Location:/console/baners/');
    }

    public function activateAction()
    {
        $id = $this->getRequest()->getParam('id');
	$active = $this->getRequest()->getParam('active');

	if(isset($id) && isset($active)){
	    $banerObj = new Console_Model_DbTable_Baners();

	    $data = array('active' => $active);
	    $banerObj->updateBaner($data,$id);

	    header('Location: /console/baners');
	}
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getparam('id');

	$banerObj = new Console_Model_DbTable_Baners();

	$baner = $banerObj->getBaner($id);
	if ($baner['type'] == 'img' || $baner['type'] == 'swf') unlink('/var/www/zagroda/public/baners/'.$baner['type'].'/'.$baner[$baner['type']]);
	$banerObj->deleteBaner($id);
	header('Location: /console/baners/');
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
	$save = $this->getRequest()->getParam('save');
	$banerObj = new Console_Model_DbTable_Baners();
	$form = $this->form;

	if (isset($save) && $save = 1){
	    $data = array(
		    'name'      => $this->getRequest()->getPost('name'),
		    'position'  => $this->getRequest()->getPost('position'),
		    'url'       => $this->getRequest()->getPost('url'),
		    'active'    => $this->getRequest()->getPost('active'),
		    'date_from' => $this->getRequest()->getPost('date_from'),
		    'date_to'   => $this->getRequest()->getPost('date_to'),
		    'html'      => 'test');

	    $html = $this->getRequest()->getPost('html');
	    if (isset($html))
		$data['html'] = $html;

	    $banerObj->updateBaner($data,$id);
	    header('Location:/console/baners/');

	}else{
	    $baner = $banerObj->getBaner($id);

	    $form->getElement('name')->setValue($baner['name']);
	    $form->getElement('position')->setValue($baner['position']);
	    $form->getElement('type')->setValue($baner['type'])
				     ->setAttrib('disabled','true');
	    $form->getElement('url')->setValue($baner['url']);
	    $form->getElement('active')->setValue($baner['active']);
	    $form->getElement('date_from')->setValue($baner['date_from']);
	    $form->getElement('date_to')->setValue($baner['date_to']);

	    $form->removeElement('img1');
	    $form->removeElement('swf1');

	    if($baner['type'] == 'img' || $baner['type'] == 'swf'){
		$form->removeElement('html');
	    }else{
		$form->getElement('html')->setValue($baner['html']);
	    }

	    $form->setAction('/console/baners/edit/?save=1&id='.$id);

	    $this->view->form = $form;
	}
    }


}











