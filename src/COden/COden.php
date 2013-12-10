<?php

/**
* Main class for Oden and it holds everything.
* @package OdenCore
*/

class COden implements ISingleton {
	private static $instance = null;
	public $config = array();
    public $request;
    public $data;
    public $db;
    public $views;
    public $session;
    public $timer = array();

	/*
	* Contructor
	*/
	protected function __construct() {
        // time page generation
        $this->timer['first'] = microtime(true); 

        // include the site specific config.php and create a ref to $ly to be used by config.php
        $oden = &$this;
    	require(ODEN_SITE_PATH.'/config.php');

        // Start a named session
        session_name($this->config['session_name']);
        session_start();
        $this->session = new CSession($this->config['session_key']);
        $this->session->PopulateFromSession();
                
        // Set default date/time-zone
        date_default_timezone_set($this->config['timezone']);
                
        // Create a database object.
        if(isset($this->config['database'][0]['dsn'])) {
            $this->db = new CMDatabase($this->config['database'][0]['dsn']);
        }
          
        // Create a container for all views and theme data
        $this->views = new CViewContainer();
	}

	/*
	* Singelton patterns, get the latest created instance or create a new one.
	* @return COden the instance of this class.
	*/
	public static function Instance() {
		if(self::$instance == null) {
			self::$instance = new COden();
		}
		return self::$instance;
	}

	/*
	* Frontcontroller, check URL and route to controllers.
	*/
	public function FrontControllerRoute() {
		// Take current url and devide it into controller, method and arguments.
	    $this->request = new CRequest($this->config['url_type']);
	    $this->request->Init($this->config['base_url']);
	    $controller = $this->request->controller;
	    $method     = $this->request->method;
	    $arguments  = $this->request->arguments;
	    
	    // Is the controller enabled in config.php?
	    $controllerExists    = isset($this->config['controllers'][$controller]);
	    $controllerEnabled   = false;
	    $className           = false;
	    $classExists         = false;

	    if($controllerExists) {
	      $controllerEnabled   = ($this->config['controllers'][$controller]['enabled'] == true);
	      $className           = $this->config['controllers'][$controller]['class'];
	      $classExists     	   = class_exists($className);
	    }

		// Check if the controller has a callable method in the controller class, if so, call it
		if($controllerExists && $controllerEnabled && $classExists) {
	      $rc = new ReflectionClass($className);
	      if($rc->implementsInterface('IController')) {
	         $formattedMethod = str_replace(array('_', '-'), '', $method);
	        if($rc->hasMethod($formattedMethod)) {
	          $controllerObj = $rc->newInstance();
	          $methodObj = $rc->getMethod($formattedMethod);
	          if($methodObj->isPublic()) {
	            $methodObj->invokeArgs($controllerObj, $arguments);
	          } else {
	            die("404. " . get_class() . ' error: Controller method not public.');          
	          }
	        } else {
	          die("404. " . get_class() . ' error: Controller does not contain method.');
	        }
	      } else {
	        die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
	      }
	    } 
	    else { 
	      die('404. Page is not found.');
	    }
	}

	/* 
	* Theme engine render, renders the views using the selcted theme.
	*/
	public function ThemeEngineRender() {
		// Save to session before output anything
	    $this->session->StoreInSession();
	  
	    // Is theme enabled?
	    if(!isset($this->config['theme'])) {
	      return;
	    }

		// Get the paths and settings for the theme.
		$themeName = $this->config['theme']['name'];
		$themePath = ODEN_INSTALL_PATH . "/theme/{$themeName}";
		$themeUrl = $this->request->base_url . "theme/{$themeName}";

		// Add stylesheet path to the $oden->data array
		$this->data['stylesheet'] = "{$themeUrl}/style.css";

		// Incude the global functions.php and the ones that are parts of the theme.
		$oden = &$this;
		include(ODEN_INSTALL_PATH . '/theme/functions.php');
		$functionsPath = "{$themePath}/functions.php";
		if(is_file($functionsPath)) {
			include $functionsPath;
		}

		// Extract $oden->data to own variables and handover to the template  file.
		extract($this->data);
		extract($this->views->GetData());  
		include("{$themePath}/default.tpl.php");
	}

}