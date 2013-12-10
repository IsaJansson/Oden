<?php
/**
* A guestbook controller 
* @package OdenCore 
*/

class CCGuestbook extends CObject implements IController{
	 private $guestbookModel;

	// Constructor 
    public function __construct() {
    	parent::__construct();
    	$this->guestbookModel = new CMGuestbook();
    }

    // Impelemnting interface IController. All controllers must have a action.
    public function Index() {
	    $this->views->SetTitle('Oden guestbook');
	    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
	    	'entries'=>$this->guestbookModel->ReadAll(), 
	    	'form_action'=>$this->request->CreateUrl('', 'handler')
	    ));
    }

    // Handels posts from the form 
    public function Handler() {
	    if(isset($_POST['doAdd'])) {
	      $this->guestbookModel->Add(strip_tags($_POST['newEntry']));
	    }
	    elseif(isset($_POST['doClear'])) {
	      $this->guestbookModel->DeleteAll();
	    }            
	    elseif(isset($_POST['doCreate'])) {
	      $this->guestbookModel->Init();
	    }
    	$this->RedirectTo($this->request->CreateUrl($this->request->controller));
    }


}