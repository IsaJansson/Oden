<?php
/*
* Holding a instance of COden to enable use of $this-> in subclasses 
* @package OdenCore
*/

class CObject {
	public $config;
	public $request;
	public $data;
	public $db;
	public $views;
    public $session;

	// Constructor 
	protected function __construct() {
		$oden = COden::Instance();
		$this->config = &$oden->config;
		$this->request = &$oden->request;
		$this->data = &$oden->data;
		$this->db = &$oden->db;
		$this->views = &$oden->views;
    	$this->session  = &$oden->session;
	}

	// Redirect to another url and store the session
    protected function RedirectTo($url) {
	    $oden = COden::Instance();
	    if(isset($oden->config['debug']['db-num-queries']) && $oden->config['debug']['db-num-queries'] && isset($oden->db)) {
	      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
	    }    
	    if(isset($oden->config['debug']['db-queries']) && $oden->config['debug']['db-queries'] && isset($oden->db)) {
	      $this->session->SetFlash('database_queries', $this->db->GetQueries());
	    }    
	    if(isset($oden->config['debug']['timer']) && $oden->config['debug']['timer']) {
	            $this->session->SetFlash('timer', $oden->timer);
	    }    
	    $this->session->StoreInSession();
	    header('Location: ' . $this->request->CreateUrl($url));
    }

}