<?php
/*
* Holding a instance of COden to enable use of $this-> in subclasses 
* @package OdenCore
*/

class CObject {
	protected $config;
	protected $request;
	protected $data;
	protected $db;
	protected $views;
    protected $session;
    protected $user;
    protected $oden;

	// Constructor 
	protected function __construct($oden=null) {
		if(!$oden) {
			$oden = COden::Instance();
		}
		$this->oden 	= &$oden;
		$this->config 	= &$oden->config;
		$this->request 	= &$oden->request;
		$this->data 	= &$oden->data;
		$this->db 		= &$oden->db;
		$this->views 	= &$oden->views;
    	$this->session  = &$oden->session;
    	$this->user 	= &$oden->user;
	}

	// Redirect to another url and store the session
    protected function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
	    $this->oden->RedirectTo($urlOrController, $method, $arguments);
    }

	/**
	 * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
     * @param string method name the method, default is index method.
     */
    protected function RedirectToController($method=null, $arguments=null) {
    	$this->oden->RedirectToController($method, $arguments);
  	}

    /**
     * Redirect to a controller and method. Uses RedirectTo().
     * @param string controller name the controller or null for current controller.
     * @param string method name the method, default is current method.
     */
    protected function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
         $this->oden->RedirectToControllerMethod($controller, $method, $arguments);;
  	}

  	protected function AddMessage($type, $message, $alternative=null) {
	     return $this->oden->AddMessage($type, $message, $alternative);
    }


    /**
     * Create an url. Uses $this->request->CreateUrl()
     *
     * @param $urlOrController string the relative url or the controller
     * @param $method string the method to use, $url is then the controller or empty for current
     * @param $arguments string the extra arguments to send to the method
     */
    protected function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
    	 return $this->oden->CreateUrl($urlOrController, $method, $arguments);
  	}

}