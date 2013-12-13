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

	// Constructor 
	protected function __construct($oden=null) {
		if(!$oden) {
			$oden = COden::Instance();
		}
		$this->config 	= &$oden->config;
		$this->request 	= &$oden->request;
		$this->data 	= &$oden->data;
		$this->db 		= &$oden->db;
		$this->views 	= &$oden->views;
    	$this->session  = &$oden->session;
    	$this->user 	= &$oden->user;
	}

	// Redirect to another url and store the session
    protected function RedirectTo($urlOrController=null, $method=null) {
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
	    header('Location: ' . $this->request->CreateUrl($urlOrController, $method));
    }

		/**
		 * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
         * @param string method name the method, default is index method.
         */
        protected function RedirectToController($method=null) {
    		$this->RedirectTo($this->request->controller, $method);
  		}

        /**
         * Redirect to a controller and method. Uses RedirectTo().
         * @param string controller name the controller or null for current controller.
         * @param string method name the method, default is current method.
         */
        protected function RedirectToControllerMethod($controller=null, $method=null) {
            $controller = is_null($controller) ? $this->request->controller : null;
            $method = is_null($method) ? $this->request->method : null;          
    		$this->RedirectTo($this->request->CreateUrl($controller, $method));
  		}

  		protected function AddMessage($type, $message, $alternative=null) {
		    if($type === false) {
		      $type = 'error';
		      $message = $alternative;
		    } else if($type === true) {
		      $type = 'success';
		    }
		    $this->session->AddMessage($type, $message);
    }


        /**
         * Create an url. Uses $this->request->CreateUrl()
         *
         * @param $urlOrController string the relative url or the controller
         * @param $method string the method to use, $url is then the controller or empty for current
         * @param $arguments string the extra arguments to send to the method
         */
        protected function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
    		return $this->request->CreateUrl($urlOrController, $method, $arguments);
  		}

}