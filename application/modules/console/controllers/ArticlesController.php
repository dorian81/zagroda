<?php

class Console_ArticlesController extends Zend_Controller_Action
{

    private $form = null;

    private $page = null;

    private $newForm = null;

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

	$title = $form->createElement('text','title');
	$pageCount = $form->createElement('text','page_count');
	$submit = $form->createElement('submit','Zapisz');

	$decorator = array(
		    'ViewHelper',
		    'Description',
		    'Errors',
		    array(array('data'=>'HtmlTag'), array('tag' => 'td')),
		    array('Label', array('tag' => 'td')),
		    array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
		);

	$title->setLabel('Tytuł')
	      ->setDecorators($decorator);
	$pageCount->setLabel('Strona')
		  ->setDecorators($decorator);
	$submit->setDecorators(array(
				'ViewHelper',
				'Description',
				'Errors',
				array(array('data'=>'HtmlTag'), array('tag' => 'td','colspan' => 2)),
				array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
			    ));

	$form->addElements(array($title,$pageCount,$submit))
	     ->setDecorators(array('FormElements',
			    array(array('data'=>'HtmlTag'),array('tag'=>'table')),
			    'Form'
			));
	$this->form = $form;
	$pform = new Zend_Form;

	$page = $pform->createElement('file','page');
	$page->setDestination('/var/www/zagroda/public/tmp');
	$submit = $pform->createElement('submit','Zmień');

	$pform->addElements(array($page,$submit));
	$this->page = $pform;

	$newForm = new Zend_Form;

	$page->setLabel('Strona (plik)')
	     ->setDecorators(array(
	     'File',
	     'Errors',
	     array(array('data'=>'HtmlTag'), array('tag' => 'td')),
	     array('Label', array('tag' => 'td')),
	     array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
	    ));
	$submit->setLabel('Zapisz')
	       ->setDecorators(array(
				'ViewHelper',
				'Description',
				'Errors',
				array(array('data'=>'HtmlTag'), array('tag' => 'td','colspan' => 2)),
				array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
			    ));
	$newForm->addElements(array($page, $pageCount, $title, $submit))
		->setDecorators(array('FormElements',
			    array(array('data'=>'HtmlTag'),array('tag'=>'table')),
			    'Form'
			));
	$this->newForm = $newForm;
    }

    public function indexAction()
    {
        $issue = $this->getRequest()->getParam('issue');
	$articlesObj = new Console_Model_DbTable_Articles();
	$articlesArr = $articlesObj->getArticles($issue);
	$new = $this->getRequest()->getParam('new');

	if (isset($new) && $new = 1) $this->view->newIssue = $new;
	$this->view->issue = $issue;
	$this->view->articles = $articlesArr;
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
	$save = $this->getRequest()->getParam('save');
	$issue = $this->getRequest()->getParam('issue');

	$articleObj = new Console_Model_DbTable_Articles();

	if (isset($save) && $save == 1){
	    $data = array(
			  'title' => $this->getRequest()->getPost('title'),
			  'page_count' => $this->getRequest()->getPost('page_count'));

	    $articleObj->updateArticle($data,$id);
	    header('Location:/console/articles?issue='.$issue);
	}else{
	    $articleArr = $articleObj->getArticle($id);

	    $form = $this->form;

	    $form->getElement('title')->setValue($articleArr['title']);
	    $form->getElement('page_count')->setValue($articleArr['page_count']);
	    $form->setAction('/console/articles/edit/?id='.$id.'&issue='.$issue.'&save=1');

	    $this->view->form = $form;
	    $this->view->page = $articleArr['page'];
	    $this->view->id = $id;
	    $this->view->issue = $issue;
	}
    }

    public function pageAction()
    {
	$id = $this->getRequest()->getParam('id');
	$issue = $this->getRequest()->getParam('issue');
	$save = $this->getRequest()->getParam('save');
	$articleObj = new Console_Model_DbTable_Articles();
	$articleArr = $articleObj->getArticle($id);

	if (isset($save) && $save == 1){
	    $file =$this->page->getValue('page');
	    require('/var/www/zagroda/application/modules/console/utils/articles.php');
	    if (upload_file($file,$issue)){
		unlink ('/var/www/zagroda/public/'.$file);
		unlink ('/var/www/zagroda/public/issues/'.$issue.'/m/'.$articleArr['page']);
		unlink ('/var/www/zagroda/public/issues/'.$issue.'/'.$articleArr['page']);
		$data = array('page' => $file);
		$articleObj->updateArticle($data, $id);
		header('Location: /console/articles/edit/?id='.$id.'&issue='.$issue);
	    }

	}else{
	    $form = $this->page;
	    $form->setAction('/console/articles/page/?save=1&id='.$id.'&issue='.$issue);

	    $this->view->form = $form;
	    $this->view->issue = $issue;
	    $this->view->img = $articleArr['page'];
	}
    }

    public function newAction()
    {
        $issue = $this->getRequest()->getParam('issue');
	$save = $this->getRequest()->getParam('save');

	if (isset($save) && $save == 1){
	    $articleObj = new Console_Model_DbTable_Articles();
	    require('/var/www/zagroda/application/modules/console/utils/articles.php');
	    $file = $this->newForm->getValue('page');
	    if (upload_file($file,$issue)){
		unlink('/var/www/zagroda/public/tmp/'.$file);
		$data = array('page'       => $file,
			      'page_count' => $this->getRequest()->getPost('page_count'),
			      'title'      => $this->getRequest()->getPost('title'),
			      'issue'      => $issue
			     );
		$articleObj->insertArticle($data);
		header('Location: /console/articles/?issue='.$issue);
	    }
	}else{
	    $this->newForm->setAction('/console/articles/new/?issue='.$issue.'&save=1');
	    $this->view->form = $this->newForm;
	}
    }

    public function deleteAction()
    {
	$id = $this->getRequest()->getParam('id');
	$issue = $this->getRequest()->getParam('issue');
	$articleObj = new Console_Model_DbTable_Articles();
	$articleArr = $articleObj->getArticle($id);

	unlink('/var/www/zagroda/public/issues/'.$issue.'/m/'.$articleArr['page']);
	unlink('/var/www/zagroda/public/issues/'.$issue.'/'.$articleArr['page']);
	$articleObj->deleteArticle($id);
	header('Location: /console/articles/?issue='.$issue);
    }


}









