<?php

class Console_IssuesController extends Zend_Controller_Action
{

    private $form = null;

    private $coverForm = null;

    private $session;

    public function init()
    {
	$session = new Zend_Session_Namespace('index');
	if (!isset($session->adminId)){
	    header('Location: /console/login');
	}else{
	    $adminArr['imie'] = $session->name;
	    $adminArr['nazwisko'] = $session->surname;
	    $this->view->admin = $adminArr;
	    $this->session = $session;
	} 

	$form = new Zend_Form();

	$number = $form->createElement('text','number');
	$ordinalNo = $form->createElement('text','ordinal_no');
	$year = $form->createElement('text','year');
	$active = $form->createElement('checkbox','active');
	$archive = $form->createElement('checkbox','archive');
	$submit = $form->createElement('submit','Zapisz');

	$decorator = array(
			'ViewHelper',
			'Description',
			'Errors',
			array(array('data'=>'HtmlTag'), array('tag' => 'td')),
			array('Label', array('tag' => 'td')),
			array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
		    );

	$number->setLabel('Numer')
	       ->setDecorators($decorator);
	$ordinalNo->setLabel('Numer Kolejny')
		  ->setDecorators($decorator);
	$year->setLabel('Rok wydania')
	     ->setDecorators($decorator);
	$active->setLabel('Publikacja')
	       ->setDecorators($decorator);
	$archive->setLabel('Archiwum')
		->setDecorators($decorator);
	$submit->setDecorators(array(
			'ViewHelper',
			'Description',
			'Errors',
			array(array('data'=>'HtmlTag'), 
			      array('tag' => 'td', 'colspan' => 2, 'align' => 'center')),
			array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
		    ));

	$form->addElements(array($number,
				 $ordinalNo,
				 $year,
				 $active,
				 $archive,
				 $submit))
	     ->setDecorators(array(
			'FormElements',
			array(array('data'=>'HtmlTag'),array('tag'=>'table')),
			'Form'
		    ));

	$this->form = $form;

	unset($form);
	$issue = $this->getRequest()->getParam('issue');
	$form = new Zend_Form;

	$cover = $form->createElement('file','cover');
	$submit = $form->createElement('submit','Zmień');

	$cover->setlabel('Okładka')
	      ->setMaxFileSize(3072000)
	      ->setDestination('/var/www/zagroda/public/tmp/');

	$new = $this->getRequest()->getParam('new');
	$action = '/console/issues/cover/?issue='.$issue.'&save=1';
	$action .= (isset($new))?'&new=1':'';
	$form->setAction($action)
	     ->setElements(array($cover,$submit));

	$this->coverForm = $form;
    }

    public function indexAction()
    {
        $issuesObj = new Console_Model_DbTable_Issues();
	$issuesArr = $issuesObj->getAll();
	$this->view->issues = $issuesArr;
    }

    public function archiveAction()
    {
        $id = $this->getRequest()->getParam('id');
	$archive = $this->getRequest()->getParam('archive');
	$issueObj = new Console_Model_DbTable_Issues();

	if ($archive == 0)
	    $issueObj->dearchive();

	$data = array('archive' => $archive);
	$issueObj->updateIssue($data,$id);
	header('Location:/console/issues/');
    }

    public function activateAction()
    {
	$id = $this->getRequest()->getParam('id');
	$active = $this->getRequest()->getParam('active');
	$issueObj = new Console_Model_DbTable_Issues();

	$data = array('active' => $active);
	$issueObj->updateIssue($data,$id);

	header('Location:/console/issues/');
    }

    public function editAction()
    {
        $save = $this->getRequest()->getParam('save');
	$id = $this->getRequest()->getParam('id');
	$issueObj = new Console_Model_DbTable_Issues();

	if (isset($save) && $save == 1){
	    $issueArr = $issueObj->getIssue($id);

	    $data = array('number' => $this->getRequest()->getPost('number'),
			  'ordinal_no' => $this->getRequest()->getPost('ordinal_no'),
			  'year' => $this->getRequest()->getPost('year'),
			  'active' => $this->getRequest()->getPost('active'),
			  'archive' => $this->getRequest()->getPost('archive'));

	    $issueObj->updateIssue($data,$issueArr['id']);
	    header('Location:/console/issues/');
	}else{
	    $form = $this->form;
	    $issueArr = $issueObj->getIssue($id);

	    $form->setAction('/console/issues/edit/?id='.$id.'&save=1');
	    $form->getElement('number')->setValue($issueArr['number']);
	    $form->getElement('ordinal_no')->setValue($issueArr['ordinal_no']);
	    $form->getElement('year')->setValue($issueArr['year']);
	    $form->getElement('active')->setValue($issueArr['active']);
	    $form->getElement('archive')->setValue($issueArr['archive']);

	    $this->view->form = $form;
	    $this->view->issue = $id;
	}
    }

    public function coverAction()
    {
	$issue = $this->getRequest()->getParam('issue');
	$save  = $this->getRequest()->getParam('save');
	$new   = $this->getRequest()->getParam('new');

	if (isset($new)){
	    $articleObj = new Console_Model_DbTable_Articles();
	    $issueObj = new Console_Model_DbTable_Issues();
	    $issueArr = $issueObj->getIssue($issue);

	    $directory=opendir('/var/www/zagroda/public/issues/'.$issue.'/m/');
	    $i=1;
	    $entries = array();
	    while($entry=readdir($directory)){
		$ext=substr($entry,count($entry)-4,3);
		if($ext=='png'||$ext=='jpg') {
		    $entries[] = $entry;
		}
	    }
	    closedir($directory);
	    sort($entries);
	    foreach ($entries as $entry){
		$data = array('page' => $entry,
			      'page_count' => $i,
			      'issue' => $issue);
		$articleObj->insertArticle($data);
		$i++;
	    }
	    unset($data);
	    $data = array ('page_count' => $i-1,'cover' => $issue);
	    $issueObj->update($data,$issueArr['id']);
	}

	if (isset($save) && $save == 1){
	    require_once('/var/www/zagroda/application/modules/console/utils/covers.php');
	    $file = '/var/www/zagroda/public/tmp/'.$this->coverForm->getValue('cover');
	    upload_cover($file,$issue);
	    if (!isset($new))
		unlink($file);
	    $location = (isset($new))? '/console/articles/?issue='.$issue.'&new=1':'/console/issues/edit/?id='.$issue;
	    header('Location:'.$location);
	}else{
	    $this->view->newIssue = $new;
	    $this->view->issue = $issue;
	    $this->view->form = $this->coverForm;
	}
    }

    public function newAction()
    {
	$step = $this->getRequest()->getParam('step');

	if (!isset($step)){
	    $form = $this->form;
	    $form->removeElement('active');
	    $form->removeElement('archive');
	    $form->setAction('/console/issues/new/?step=1');

	    $this->view->form = $form;
	}else if ($step = 1){
	    $issueObj = new Console_Model_DbTable_Issues();

	    $data = array('number'        => $this->getRequest()->getPost('number'),
			  'ordinal_no'    => $this->getRequest()->getPost('ordinal_no'),
			  'year'          => $this->getRequest()->getpost('year'),
			  'active'        => '0',
			  'archive'       => '1',
			  'admin'         => $this->session->adminId,
			  'creation_date' => date('Y-m-d H:j:s'));
	    $issueObj->insertIssue($data);
	    $this->view->data = $data;
	    $this->view->issue = $data['ordinal_no'];
	    $this->view->step = 1;
	}
    }
}