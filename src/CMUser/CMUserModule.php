<?php
/**
* To manage the module
*/
class CMUserModule extends CMUser {

	/**
	 * Manage install/update/deinstsll  and equal actions.
	 * @param string $action the action to carry out
	 * @param array $args extra arguments
	 */
	public function Manage($action=null, $args=null) {
		switch($action){
			case 'install-root':

			// Need to have arguments to create root user
			if(!is_array($args)) {
				return array('error', 'CMUserModule::Manage() says - Missing arguments to create the root user');
			}
			$rootEmail = $args['rootEmail'];
			$rootUserName = $args['rootUserName'];
			$rootPassword = $args['rootPassword'];
			$password = $this->CreatePassword($rootPassword);

			// Create the tables if not alreade there 
			$this->db->ExecuteQuery(self::SQL('create table user'));
			$this->db->ExecuteQuery(self::SQL('create table group'));
			$this->db->ExecuteQuery(self::SQL('create table user2group'));

			// Root user already exists?
			$this->db->ExecuteQurey(self::SQL('select user by id'), array(1));
			if($this->db->RowCount()) {
				return array('error', 'You can not create a root user since there is already a root user with id=1');
			}

			$this->db->ExecuteQuery(self::SQL('insert into user'), array($rootUserName, 'The Root User', null, $rootEmail, $password['algorithm'], $password['salt'], $password['password']));
			$idRootUser = $this->db->LastInsertId();
			$this->db->ExecuteQuery(self::SQL('insert into user'), array('anonymous', 'anonymous user', null, null, 'plain', null, null));
			$this->db->ExecuteQuery(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
			$isAdminGroup = $this->db->LastInsertId();
			$this->db->ExecuteQuery(self::SQL('insert into group'), array('user', 'The User Group'));
			$idUserGroup = $this->db->LastInsertId();
			$this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
        	$this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
        	return array('success', 'Database tables for users and groups are ready, as is the root user.');
        break;

	    case 'supported-actions':
	    	$actions = array('install-root');
	    	return array('success', 'Supporting the following actions: !actions.', array('!actions'=>implode(', ', $actions)), 'actions'=>$actions);
	    break;
	    
	    default:
	    	return array('info', 'Action not supported by this module.');
	    break;	
		}	
	}
}