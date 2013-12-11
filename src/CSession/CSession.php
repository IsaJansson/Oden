<?php
 /**
* Wrapper for session, read and store values on session. Maintains flash values for one pageload.
* @package OdenCore
*/

class CSession {

	// Members
	private $key;
	private $data = array();
	private $flash = null;

	// Constructor
	public function __construct($key) {
   		$this->key = $key;
  	}

  	// Set values 
  	public function __set($key, $value) {
  		$this->data[$key] = $value;
  	}

  	// Get values 
  	public function __get($key) {
  		return isset($this->data[$key]) ? $this->data[$key] : null;
  	}

  	// Get, Set or Unset the authenticated user
  	public function SetAuthenticatedUser($profile) {
  		$this->data['authenticated_user'] = $profile;
  	}
  	public function UnsetAuthenticatedUser() {
  		unset($this->data['authenticated_user']);
  	}
  	public function GetAuthenticatedUser() {
  		return $this->authenticated_user;
  	}

	
	// Store values in session
	public function StoreInSession(){
	$_SESSION[$this->key] = $this->data;
	}

	// Set flash values to be remembered one page request.
	public function SetFlash($key, $value) {
		$this->data['flash'][$key] = $value;
	}

	// Get flash values
	public function GetFlash($key) {
		return isset($this->flash[$key]) ? $this->flash[$key] : null;
	}

	// Stores values from this object into a session
	public function PopulateFromSession() {
		if(isset($_SESSION[$this->key])) {
			$this->data = $_SESSION[$this->key];
			if(isset($this->data['flash'])) {
				$this->flash = $this->data['flash'];
				unset($this->data['flash']);
			}
		}
	}

	// Add message to be displayed to user on next pageload. Store in flash.
    // @param $type string the type of message, for example: notice, info, success, warning, error
  	public function AddMessage($type, $message) {
    $this->data['flash']['messages'][] = array('type' => $type, 'message' => $message);
  	}

  	// Get messages, if any. Each message is composed of a key and value. Use the key for styling.
    public function GetMessages() {
    	return isset($this->flash['messages']) ? $this->flash['messages'] : null;
  }


}