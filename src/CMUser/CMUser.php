<?php

/**
* A model for autenticating a user
* @package OdenCore
*/

class CMUser extends CObject implements IHasSQL, ArrayAccess {

	public $profile = array();

	// Constructor
	public function __construct($oden=null) {
		parent::__construct($oden);
		$profile = $this->session->GetAuthenticatedUser();
	    $this->profile = is_null($profile) ? array() : $profile;
	    $this['isAuthenticated'] = is_null($profile) ? false : true;
	}

    // Implementing ArrayAccess for $this->profile
    public function offsetSet($offset, $value) { if (is_null($offset)) { $this->profile[] = $value; } else { $this->profile[$offset] = $value; }}
    public function offsetExists($offset) { return isset($this->profile[$offset]); }
    public function offsetUnset($offset) { unset($this->profile[$offset]); }
    public function offsetGet($offset) { return isset($this->profile[$offset]) ? $this->profile[$offset] : null; }

	// Implementing IHasSQL and encapsulating all SQL used by this class 
	public static function SQL($key=null) {
		$queries = array(
	      'drop table user'         => "DROP TABLE IF EXISTS User;",
	      'drop table group'        => "DROP TABLE IF EXISTS Groups;",
	      'drop table user2group'   => "DROP TABLE IF EXISTS User2Groups;",
	      'create table user'       => "CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, email TEXT, algorithm TEXT, salt TEXT, password TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
	      'create table group'      => "CREATE TABLE IF NOT EXISTS Groups (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
	      'create table user2group' => "CREATE TABLE IF NOT EXISTS User2Groups (idUser INTEGER, idGroups INTEGER, created DATETIME default (datetime('now')), PRIMARY KEY(idUser, idGroups));",
	      'insert into user'        => 'INSERT INTO User (acronym,name,email,algorithm,salt,password) VALUES (?,?,?,?,?,?);',
	      'insert into group'       => 'INSERT INTO Groups (acronym,name) VALUES (?,?);',
	      'insert into user2group'  => 'INSERT INTO User2Groups (idUser,idGroups) VALUES (?,?);',
	      'check user password'     => 'SELECT * FROM User WHERE (acronym=? OR email=?);',
	      'select from user'     	=> 'SELECT * FROM User;',
	      'get group memberships'   => 'SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;',
	      'update profile'          => "UPDATE User SET name=?, email=?, updated=datetime('now') WHERE id=?;",
	      'update password'         => "UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now') WHERE id=?;",
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
	      $password = $this->CreatePassword('root');
	      $this->db->ExecuteQuery(self::SQL('insert into user'), array('root', 'The Administrator', 'isa.jansson@hotmail.com', $password['algorithm'], $password['salt'], $password['password']));
	      $idRootUser = $this->db->LastInsertId();
	      $password = $this->CreatePassword('doe');
	      $this->db->ExecuteQuery(self::SQL('insert into user'), array('doe', 'John/Jane Doe', 'doe@dbwebb.se', $password['algorithm'], $password['salt'], $password['password']));
	      $idDoeUser = $this->db->LastInsertId();
	      $this->db->ExecuteQuery(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
	      $idAdminGroup = $this->db->LastInsertId();
	      $this->db->ExecuteQuery(self::SQL('insert into group'), array('user', 'The User Group'));
	      $idUserGroup = $this->db->LastInsertId();
	      $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
	      $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
	      $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idDoeUser, $idUserGroup));
	      $this->AddMessage('success', 'Successfully created the database tables and created a default admin user as root:root and an ordinary user as doe:doe.');
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
		$user = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('check user password'), array($acronymOrEmail, $acronymOrEmail));
	    $user = (isset($user[0])) ? $user[0] : null;
	    if(!$user) {
	      return false;
	    } 
	    else if(!$this->CheckPassword($password, $user['algorithm'], $user['salt'], $user['password'])) {
      		return false;
    	}
	    unset($user['algorithm']);
	    unset($user['salt']);
	    unset($user['password']);
	    if($user) {
	      $user['isAuthenticated'] = true;
	      $user['groups'] = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('get group memberships'), array($user['id']));
	      foreach($user['groups'] as $val) {
	        if($val['id'] == 1) {
	          $user['hasRoleAdmin'] = true;
	        }
	        if($val['id'] == 2) {
	          $user['hasRoleUser'] = true;
	        }
	      }
	      $this->profile = $user;
	      $this->session->SetAuthenticatedUser($this->profile);
	    }
	    return ($user != null);
	}

	// Logout
	public function Logout() {
	    $this->session->UnsetAuthenticatedUser();
    	$this->profile = array();
    	$this->session->AddMessage('success', "You have logged out.");
	} 

	public function Save() {
		$this->db->ExecuteQuery(self::SQL('update profile'), array($this['name'], $this['email'], $this['id']));
	    $this->session->SetAuthenticatedUser($this->profile);
	    return $this->db->RowCount() === 1;
	}

	public function ChangePassword($plain) {
		$password = $this->CreatePassword($plain);
	    $this->db->ExecuteQuery(self::SQL('update password'), array($password['algorithm'], $password['salt'], $password['password'], $this['id']));
	    return $this->db->RowCount() === 1;
	}

	/** Create password
	* $param $plain string, the password plain text to use as base
    * $param $salt boolean, should  we use salt or not when creating the password? default is true
    * @return array with 'salt' and 'password' 
    */
	public function CreatePassword($plain, $algorithm=null) {
	    $password = array(
	      'algorithm'=>($algorithm ? $algorithm : COden::Instance()->config['hashing_algorithm']),
	      'salt'=>null
	    );
	    switch($password['algorithm']) {
	      case 'sha1salt': $password['salt'] = sha1(microtime()); $password['password'] = sha1($password['salt'].$plain); break;
	      case 'md5salt': $password['salt'] = md5(microtime()); $password['password'] = md5($password['salt'].$plain); break;
	      case 'sha1': $password['password'] = sha1($plain); break;
	      case 'md5': $password['password'] = md5($plain); break;
	      case 'plain': $password['password'] = $plain; break;
	      default: throw new Exception('Unknown hashing algorithm');
	    }
	    return $password;
	}

	// Check if password matches
    public function CheckPassword($plain, $algorithm, $salt, $password) {
	    switch($algorithm) {
	      case 'sha1salt': return $password === sha1($salt.$plain); break;
	      case 'md5salt': return $password === md5($salt.$plain); break;
	      case 'sha1': return $password === sha1($plain); break;
	      case 'md5': return $password === md5($plain); break;
	      case 'plain': return $password === $plain; break;
	      default: throw new Exception('Unknown hashing algorithm');
	    }
    }

	/**
    * Create new user.
    * @param $acronym string
    * @param $password string
    * @param $name string 
    * @param $email string 
    * @return boolean, true if user was created or else false and sets failure message in session.
    */
    public function Create($acronym, $password, $name, $email) {
	    $pwd = $this->CreatePassword($password);
	    $this->db->ExecuteQuery(self::SQL('insert into user'), array($acronym, $name, $email, $pwd['algorithm'], $pwd['salt'], $pwd['password']));
	    if($this->db->RowCount() == 0) {
	      $this->AddMessage('error', "Failed to create user.");
	      return false;
	    }
	    return true;
   }



}