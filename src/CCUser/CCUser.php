<?php

/**
* A user controller to manage login and manage the user profile.
* @package OdenCore
*/

class CCUser extends CObject implements IController {
	private $userModel;

	// Constructor 
	public function __construct() {
		parent::__construct();
		$this->userModel = new CMUser();
	}

	// Show profile information of the user 
	public function Index() {
		$this->views->SetTitle('User Profile');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
			'is_authenticated'=>$this->userModel->IsAuthenticated(),
			'user'=>$this->userModel->GetUserProfile(),
			));
	}

	// Authenticate and login a user
	public function Login($acronymOrEmail=null, $password=null) {
	    $form = new CForm();
	    $form->AddElement('acronym', array('label'=>'Acronym or email:', 'type'=>'text'));
	    $form->AddElement('password', array('label'=>'Password:', 'type'=>'password'));
	    $form->AddElement('doLogin', array('value'=>'Login', 'type'=>'submit', 'callback'=>array($this, 'DoLogin')));
    	$form->CheckIfSubmitted();

	    $this->views->SetTitle('Login');
	    $this->views->AddInclude(__DIR__ . '/login.tpl.php', array('login_form'=>$form->GetHTML())); 
	}

	// Logout
	public function Logout() {
		$this->userModel->Logout();
		$this->RedirectToController();
	}

	// Initiate the user database 
	public function Init() {
		$this->userModel->Init();
		$this->RedirectToController();
	}

	// log in user if callback from a submitted form
	public function DoLogin($form) {
		if($this->user->Login($form->GetValue('acronym'), $form->GetValue('password'))) {
			$this->RedirectToController('profile');
		}
		else {
			$this->RedirectToController('login');
		}
	}

	/* View and edit user profile 
	public function Profile(){
		$form = new CFormUserProfile($this, $this->user)
		$form->
	}*/


}