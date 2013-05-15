<?php

class Console_NewsController extends Zend_Controller_Action
{

    private $form = null;

    private $issue = null;

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

	$this->issue = $this->getRequest()->getParam('issue');
	$issueObj = new Console_Model_DbTable_Issues();
	$issueArr = $issueObj->getIssue($this->issue);
	$pageCount = $issueArr['page_count'];
	for ($i=1;$i<$pageCount;$i++){
	    $pages[$i] = $i;
	}

	$form = new Zend_Form();

	$h3 = $form->createElement('text','h3');
	$h1 = $form->createElement('text','h1');
	$content = $form->createElement('textarea','content');
	$page = $form->createElement('select','page');
	$submit = $form->createElement('submit','Zapisz');

	$decorator = array(
		    'ViewHelper',
		    'Description',
		    'Errors',
		    array(array('data'=>'HtmlTag'), array('tag' => 'td')),
		    array('Label', array('tag' => 'td')),
		    array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
		);

	$h3->setLabel('Tytuł (H3)')
	   ->setDecorators($decorator);
	$h1->setLabel('Tytuł (H1)')
	   ->setDecorators($decorator);
	$content->setLabel('Treść')
		->setAttrib('id','edit1')
		->setDecorators($decorator);
	$page->setLabel('Strona')
	     ->addMultiOptions($pages)
	     ->setDecorators($decorator);
	$submit->setdecorators(array(
		    'ViewHelper',
		    'Description',
		    'Errors',
		    array(
			array('data'=>'HtmlTag'), 
			array('tag' => 'td', 'colspan' => 2)),
		    array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
		));

	$form->addElements(array($h3,$h1,$content,$page,$submit));
	$form->setDecorators(array(
		    'FormElements',
		    array(array('data'=>'HtmlTag'),array('tag'=>'table')),
		    'Form'
		));

	$this->form = $form;
    }

    public function indexAction()
    {
	$save = $this->getRequest()->getParam('save');
	$newsObj = new Console_Model_DbTable_News();

	if (isset($save) && $save == 1){
	    $data = array('h1' => $this->getRequest()->getPost('h1'),
			  'h3' => $this->getRequest()->getPost('h3'),
			  'content' => $this->getRequest()->getPost('content'),
			  'page' => $this->getRequest()->getPost('page'),
			  'issue' => $this->issue);
	    $newsObj->insertNews($data);
	    header('Location:/console/news/?issue='.$this->issue);
	}else{
	    $this->form->setAction('/console/news/?issue='.$this->issue.'&save=1');
	    $newsArr = $newsObj->getNews($this->issue);

	    $pages = $this->form->getElement('page')->getMultiOptions();
	    foreach ($newsArr as $news){
		unset($pages[$news['page']]);
	    }
	    $this->form->getElement('page')->setMultiOptions($pages);

	    $this->view->news = $newsArr;
	    $this->view->issue = $this->issue;
	    $this->view->form =$this->form;
	}
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
	$save = $this->getRequest()->getParam('save');
	$newsObj = new Console_Model_DbTable_News();
	$form = $this->form;

	if (isset($save) && $save == 1){
	    $data = array('h1' => $this->getRequest()->getPost('h1'),
			  'h3' => $this->getRequest()->getPost('h3'),
			  'content' => $this->getRequest()->getPost('content'),
			  'page' => $this->getRequest()->getPost('page'));
	    $newsObj->updateNews($data,$id);
	    header('Location:/console/news/?issue='.$this->issue);

	}else{
	    $newsSingleArr = $newsObj->getSingleNews($id);
	    $newsArr = $newsObj->getNews($this->issue);

	    $form->setAction('/console/news/edit/?id='.$id.'&issue='.$this->issue.'&save=1');
	    $form->getElement('h1')->setValue($newsSingleArr['h1']);
	    $form->getElement('h3')->setValue($newsSingleArr['h3']);
	    $form->getElement('content')->setValue($newsSingleArr['content']);
	    $pages = $form->getElement('page')->getMultiOptions();
	    foreach($newsArr as $news){
		if ($news['page'] != $newsSingleArr['page']) 
		    unset($pages[$news['page']]);
	    }
	    $form->getElement('page')->setMultiOptions($pages);
	    $form->getElement('page')->setValue($newsSingleArr['page']);

	    $this->view->form = $form;
	    $this->view->issue = $this->issue;
	    $this->view->news = $newsArr;
	}
    }

    public function deleteAction()
    {
	$newsObj = new Console_Model_DbTable_News();
	$id = $this->getRequest()->getParam('id');

	$newsObj->deleteNews($id);
	header('Location:/console/news/?issue='.$this->issue);
    }


}





