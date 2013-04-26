<?php

class Console_AdminsController extends Zend_Controller_Action
{

    private $pwd = null;

    private $edit = null;

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

	$pwd = new Zend_Form();

	$pass = $pwd->createElement('password','pass');
	$passR = $pwd->createElement('password','pass_r');
	$submit = $pwd->createElement('submit','Zapisz');

	$decorator = array(
		'ViewHelper',
		'Description',
		'Errors',
		array(array('data'=>'HtmlTag'), array('tag' => 'td')),
		array('Label', array('tag' => 'td')),
		array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
    	    ); 

	$pass->setLabel('Nowe hasło')
	     ->setDecorators($decorator);
	$passR->setLabel('Powtórz hasło')
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

	$pwd->addElements(array($pass,$passR,$submit));
	$pwd->setDecorators(array(
		'FormElements',
		array(array('data'=>'HtmlTag'),array('tag'=>'table')),
		'Form'
	    ));

	$this->pwd = $pwd;

	$edit = new Zend_Form();

	$name = $edit->createElement('text','imie');
	$sname = $edit->createElement('text','nazwisko');
	$login = $edit->createElement('text','login');
	$submit = $edit->createElement('submit','Zapisz');

	$name->setLabel('Imię')
	     ->setDecorators($decorator);
	$sname->setLabel('Nazwisko')
	      ->setDecorators($decorator);
	$login->setlabel('Login')
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

	$edit->addElements(array($name,$sname,$login,$submit));
	$edit->setDecorators(array(
		'FormElements',
		array(array('data'=>'HtmlTag'),array('tag'=>'table')),
		'Form'
	    ));

	$this->edit = $edit;
    }

    public function indexAction()
    {
        $adminsObj = new Console_Model_DbTable_Admins();
	$adminsArr = $adminsObj->getAll();
	$this->view->admins = $adminsArr;
    }

    public function activateAction()
    {
        $id = $this->getRequest()->getParam('id');
	$active = $this->getRequest()->getparam('active');

	$adminsObj = new Console_Model_DbTable_Admins();

	if (isset($id) && isset($active)){
	    $data = array('active' => $active);
	    $adminsObj->updateAdmin($data,$id);
	}
	header('Location: /console/admins/');
    }

    public function pwdAction()
    {
        $id = $this->getRequest()->getParam('id');
	$save = $this->getRequest()->getParam('save');

	$adminObj = new Console_Model_DbTable_Admins();

	if (isset($save) && $save == 1){
	    $form['pass'] = $this->getRequest()->getPost('pass');
	    $form['pass_r'] = $this->getRequest()->getPost('pass_r');

	    if ($form['pass'] == $form['pass_r'] && $form['pass'] != ''){
		$data = array('pass' => crypt($form['pass'],'zAGROda'));
		$adminObj->updateAdmin($data,$id);
		header('Location: /console/admins/');
	    }else{
		header('Location: /console/admins/pwd/?id='.$id.'&error=1');
	    }
	}else{
	    $err = $this->getRequest()->getParam('error');

	    $this->pwd->setAction('/console/admins/pwd/?id='.$id.'&save=1')
		      ->setMethod('Post');
	    if (isset($err) && $err == 1)
		$errMsg='Podane hasła nie są identyczne';
	    else $errMsg = '';

	    $this->view->error = $errMsg;
	    $this->view->form = $this->pwd;
	}
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
	$save = $this->getRequest()->getParam('save');

	$adminObj = new Console_Model_DbTable_Admins();

	if (isset($save) && $save == 1){
	    $data = array(
		'imie' => $this->getRequest()->getPost('imie'),
		'nazwisko' => $this->getRequest()->getPost('nazwisko'),
		'login' => $this->getRequest()->getPost('login'));

	    $adminObj->updateAdmin($data,$id);
	    header('Location: /console/admins/');
	}else{
	    $adminArr = $adminObj->selectAdmin($id);
	
	    $this->edit->getElement('imie')->setValue($adminArr['imie']);
	    $this->edit ->getElement('nazwisko')->setValue($adminArr['nazwisko']);
	    $this->edit->getElement('login')->setValue($adminArr['login']);
	    $this->edit->setAction('/console/admins/edit/?save=1&id='.$id);
	    $this->edit->setMethod('Post');
	    $this->view->form = $this->edit;
	}
    }

    public function newAction()
    {
        $id = $this->getRequest()->getParam('id');
	$save = $this->getRequest()->getParam('save');

	$adminObj = new Console_Model_DbTable_Admins();

	if (isset($save) && $save == 1){
	    $data = array(
		'imie' => $this->getRequest()->getPost('imie'),
		'nazwisko' => $this->getRequest()->getPost('nazwisko'),
		'login' => $this->getRequest()->getPost('login'));

	    $adminObj->insertAdmin($data);
	    $id = $adminObj->selectMaxId();

	    header('Location: /console/admins/pwd/?id='.$id);
	}else{
	    
	    $this->edit->setAction('/console/admins/new/?save=1');
	    $this->edit->setMethod('Post');
	    $this->view->form = $this->edit;
	}
    }


}









