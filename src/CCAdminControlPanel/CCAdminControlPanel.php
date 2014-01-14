<?php
/**
* Admin control panel to manage admin stuff 
* @package OdenCore
*/
class CCAdminControlPanel extends CObject implements IController {
	
	// Constructor 
	public function __construct() {
		parent::__construct();
	}

	// Show profile information
	public function Index() {
		$this->views->SetTitle('Admin Control Panel')
					->AddInclude(__DIR__ . '/index.tpl.php', array('is_authenticated'=>$this->user['isAuthenticated'], 'user'=>$this->user), 'primary')
					->AddInclude(__DIR__ . '/sidebar.tpl.php', array('is_authenticated'=>$this->user['isAuthenticated'], 'user'=>$this->user), 'sidebar');
	}

	public function Groups() {
		$users = new CMUser();
		$this->views->SetTitle('Manage groups')
                ->AddInclude(__DIR__ . '/groups.tpl.php', array(
                  'is_authenticated'=>$this->user['isAuthenticated'], 
                  'user'=>$this->user,
                  'allgroups' => $users->GetAllGroups(),
                ))
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('is_authenticated'=>$this->user['isAuthenticated'],'user'=>$this->user), 'sidebar');	}

	public function Users() {
		$users = new CMUser();
		$this->views->SetTitle('Manage users')
                ->AddInclude(__DIR__ . '/users.tpl.php', array(
                  'is_authenticated'=>$this->user['isAuthenticated'], 
                  'user'=>$this->user,
                  'allusers' => $users->GetAllUsers(),
                ))
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('is_authenticated'=>$this->user['isAuthenticated'],'user'=>$this->user), 'sidebar');
	}

	public function Content() {
		$content = new CMContent();
		$this->views->SetTitle('Manage content')
                ->AddInclude(__DIR__ . '/content.tpl.php', array(
                  'is_authenticated'=>$this->user['isAuthenticated'], 
                  'user'=>$this->user,
                  'allcontent' => $content->ListAll(),
                ))
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('is_authenticated'=>$this->user['isAuthenticated'],'user'=>$this->user), 'sidebar');

		
	}

	public function Edit($id=null) {
		$users = new CMUser();
		if(isset($id)){
			$allGroups = $users->GetAllGroups();        
	    	$memberships = $users->GetMemberships($id);
			$form = new CFormAdminUsers($this, $users->GetUser($id), $allGroups, $memberships);
		    $form->check();
		    $this->views->SetTitle('Edit user')
	                ->AddInclude(__DIR__ . '/edituser.tpl.php', array(
	                  'is_authenticated'=>$this->user['isAuthenticated'], 
	                  'user'=>$this->user,
	                  'edituser'=>$users->GetUser($id),
	                  'profile_form'=>$form->GetHTML(),
	                ))
	                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('is_authenticated'=>$this->user['isAuthenticated'],'user'=>$this->user), 'sidebar');
        }
	}

	public function Create() {
		$form = new CFormUserCreate($this);
	    if($form->Check() === false) {
	      $this->AddMessage('notice', 'You must fill in all values.');
	      $this->RedirectToController('create');
	    }else {

	    }
	    $this->views->SetTitle('Create new user')
	                ->AddInclude(__DIR__ . '/createuser.tpl.php', array('form' => $form->GetHTML())) 
	                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('is_authenticated'=>$this->user['isAuthenticated'],'user'=>$this->user), 'sidebar');
	}

	public function DoCreate($form) {
	    if($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
	      $this->AddMessage('error', 'Password does not match or is empty.');
	      $this->RedirectToController('create');
	    }
	    else if($this->user->Create($form['acronym']['value'], 
	                           $form['password']['value'],
	                           $form['name']['value'],
	                           $form['email']['value']
	                           )) {
	      $this->AddMessage('success', "You have successfully created a new account.");
	  	  $this->RedirectToController('users');
	    } 
	    else {
	      $this->AddMessage('notice', "Failed to create an account.");
	      $this->RedirectToController('create');
	    }
	}

	public function EditGroups($id = null) {
		$group = new CMUser();
		if(isset($id)) {
			$form = new CFormAdminGroups($this, $group->GetGroup($id));
			$form->Check();
		    $this->views->SetTitle('Group Profile')
		                ->AddInclude(__DIR__ . '/editgroup.tpl.php', array(
		                  'is_authenticated'=>$this->user['isAuthenticated'], 
		                  'user'=>$this->user,
		                  'editgroup' => $group->GetGroup($id),
		                  'group_form'=>$form->GetHTML(),
		                ))
		                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('is_authenticated'=>$this->user['isAuthenticated'],'user'=>$this->user), 'sidebar');
	    }
	}


	public function CreateGroup() {
		$form = new CFormAdminCreateGroups($this);
	    if($form->Check() === false) {
	      $this->AddMessage('notice', 'You must fill in all values.');
	      $this->RedirectToController('CreateGroup');
	    }
	    $this->views->SetTitle('Create group')
	                ->AddInclude(__DIR__ . '/creategroup.tpl.php', array('form' => $form->GetHTML()), 'primary')
	                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('is_authenticated'=>$this->user['isAuthenticated'],'user'=>$this->user), 'sidebar');
	}

	public function DoCreateGroup($form) {
	  $group = new CMUser();
      if($group->CreateGroup($form['acronym']['value'], $form['name']['value'])) {
      $this->AddMessage('success', "You have successfully created {$form['name']['value']}.");
      $this->RedirectToController('groups');
   	  } else {
      $this->AddMessage('notice', "Failed to create an group.");
      $this->RedirectToController('creategroup');
      }
	}


	public function DoSaveGroup($form) {
		$edit = new CMUser();
	    $res = $edit->SaveGroup($form['acronym']['value'], $form['name']['value'], $form['id']['value']);
	    $this->AddMessage($res, 'Saved group.', 'Failed saving group.');
	}

	public function DoDeleteGroup($form) {
		$delete = new CMUser();
	    $res = $delete->DeleteGroup($form['id']['value']); 
	    $this->AddMessage($res, "You have successfully deleted {$form['name']['value']}.", "Failed to delete {$form['name']['value']}.");
	    $this->RedirectToController('groups');
	}

	public function DoDeleteProfile($form) {
		  $this->user->DeleteUser($form['id']['value']);
	      $this->RedirectToController('users');
	}

	public function DoSaveProfile($form) {
		$edit = new CMUser();
		if($edit->SaveProfile($form['name']['value'], $form['email']['value'], $form['id']['value'], $form['groups'])) {
			$this->AddMessage('success', 'New profile is saved.');
		}else {
			$this->AddMessage('notice', 'Failed to save profile');
		}
	}

	    // Change the password.
    public function DoChangePassword($form) {
	    if($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
	      $this->AddMessage('error', 'Password does not match or is empty.');
	    } else {
	      $ret = $this->user->ChangePassword($form['password']['value']);
	      $this->AddMessage($ret, 'Saved new password.', 'Failed to update password.');
	    }
	    $this->RedirectToController('profile');
    }


	
}