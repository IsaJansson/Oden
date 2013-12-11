<?php

/**
* A model for autenticating a user
* @package OdenCore
*/

class CMUser extends CObject implements IHasSQL {

	// Constructor
	public function __construct($oden=null) {
		parent::__construct($oden);
	}

	// Implementing IHasSQL and encapsulating all SQL used by this class 
	public static function SQL($key=null) {
		 $queries = array(
		      'drop table user'         => "DROP TABLE IF EXISTS User;",
		      'drop table group'        => "DROP TABLE IF EXISTS Groups;",
		      'drop table user2group'   => "DROP TABLE IF EXISTS User2Groups;",
		      'create table user'       => "CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, email TEXT, password TEXT, created DATETIME default (datetime('now')));",
		      'create table group'      => "CREATE TABLE IF NOT EXISTS Groups (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, created DATETIME default (datetime('now')));",
		      'create table user2group' => "CREATE TABLE IF NOT EXISTS User2Groups (idUser INTEGER, idGroups INTEGER, created DATETIME default (datetime('now')), PRIMARY KEY(idUser, idGroups));",
		      'insert into user'        => 'INSERT INTO User (acronym,name,email,password) VALUES (?,?,?,?);',
		      'insert into group'       => 'INSERT INTO Groups (acronym,name) VALUES (?,?);',
		      'insert into user2group'  => 'INSERT INTO User2Groups (idUser,idGroups) VALUES (?,?);',
		      'check user password'     => 'SELECT * FROM User WHERE password=? AND (acronym=? OR email=?);',
		      'get group memberships'   => 'SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;',
		     );
		if(!isset($queries[$key])) {
      		throw new Exception("No such SQL query, key '$key' was not found.");
    	}
		return $queries[$key];
	}

	// Initiate database and create appropriate tables 
	public function Init() {
	    try {
	      $this->db->ExecuteQuery(self::SQL('drop table user2group'));
	      $this->db->ExecuteQuery(self::SQL('drop table group'));
	      $this->db->ExecuteQuery(self::SQL('drop table user'));
	      $this->db->ExecuteQuery(self::SQL('create table user'));
	      $this->db->ExecuteQuery(self::SQL('create table group'));
	      $this->db->ExecuteQuery(self::SQL('create table user2group'));
	      $this->db->ExecuteQuery(self::SQL('insert into user'), array('root', 'The Administrator', 'isa.jansson@hotmail.com', 'root'));
	      $idRootUser = $this->db->LastInsertId();
	      $this->db->ExecuteQuery(self::SQL('insert into user'), array('doe', 'John/Jane Doe', 'doe@dbwebb.se', 'doe'));
	      $idDoeUser = $this->db->LastInsertId();
	      $this->db->ExecuteQuery(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
	      $idAdminGroup = $this->db->LastInsertId();
	      $this->db->ExecuteQuery(self::SQL('insert into group'), array('user', 'The User Group'));
	      $idUserGroup = $this->db->LastInsertId();
	      $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
	      $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
	      $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idDoeUser, $idUserGroup));
	      $this->session->AddMessage('alert', 'Successfully created the database tables and created a default admin user as root:root and an ordinary user as doe:doe.');
	    } catch(Exception$e) {
	      die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
	    }
	}

	/**
	* Login by autenticating username and password. store userinfo in session if autenticated.
	* @param string $acronymOrEmail 
	* @param string $password the password that matches the acronym or email
	* @return booelan true if they match otherwise false 
	*/
	public function Login($acronymOrEmail, $password) {
	    $user = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('check user password'), array($password, $acronymOrEmail, $acronymOrEmail));
	    $user = (isset($user[0])) ? $user[0] : null;
	    unset($user['password']);
	    if($user) {
	      $user['groups'] = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('get group memberships'), array($user['id']));
	      foreach($user['groups'] as $val) {
	        if($val['id'] == 1) {
	          $user['hasRoleAdmin'] = true;
	        }
	        if($val['id'] == 2) {
	          $user['hasRoleUser'] = true;
	        }
	      }
	      $this->session->SetAuthenticatedUser($user);
	      $this->session->AddMessage('success', "Welcome '{$user['name']}'.");
	    } 
	    else {
	      $this->session->AddMessage('notice', "Could not login, user does not exists or password did not match.");
	    }
	    return ($user != null);
	}

	// Logout
	public function Logout() {
		$this->session->UnsetAuthenticatedUser();
		$this->session->AddMessage('success', "You have logged out.");
	} 

	// Does the session contain a authenticated user?
	public function IsAuthenticated() {
		return ($this->session->GetAuthenticatedUser() != false);
	}

	/** 
	* Get profile information on user 
	* @return array with user profile or null if the user is anonymous.
	*/
	public function GetUserProfile() {
		return $this->session->GetAuthenticatedUser();
	}

	public function GetAcronym() {
		$profile = $this->GetUserProfile();
		return isset($profile['acronym']) ? $profile['acronym'] : null;
	}

	public function IsAdministrator() {
		$profile = $this->GetUserProfile();
		return isset($profile['hasRoleAdmin']) ? $profile['hasRoleAdmin'] : null;
	}


}