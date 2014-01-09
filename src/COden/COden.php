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
    public $user;
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

         // Create a object for the user
	    	$this->user = new CMUser($this);
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

		// Get the paths and settings for the theme
		$themePath = ODEN_INSTALL_PATH . '/' . $this->config['theme']['path'];
		$themeUrl  = $this->request->base_url . $this->config['theme']['path'];

		// Is there a parent theme?
    	$parentPath = null;
    	$parentUrl = null;
   		if(isset($this->config['theme']['parent'])) {
      		$parentPath = ODEN_INSTALL_PATH . '/' . $this->config['theme']['parent'];
      		$parentUrl  = $this->request->base_url . $this->config['theme']['parent'];
   		}
    
    	// Add stylesheet name to the $oden->data array
   		$this->data['stylesheet'] = $this->config['theme']['stylesheet'];

   		// Make the theme urls available as part of $oden
    	$this->themeUrl = $themeUrl;
    	$this->themeParentUrl = $parentUrl;

		// Map menu to region if defined
    	if(is_array($this->config['theme']['menu_to_region'])) {
      		foreach($this->config['theme']['menu_to_region'] as $key => $val) {
        		$this->views->AddString($this->DrawMenu($key), null, $val);
      		}
    	}

		// Incude the global functions.php and the ones that are parts of the theme.
		$oden = &$this;
		include(ODEN_INSTALL_PATH . '/theme/functions.php');
		if($parentPath) {
      		if(is_file("{$parentPath}/functions.php")) {
        		include "{$parentPath}/functions.php";
      		}
    	}
   		if(is_file("{$themePath}/functions.php")) {
      		include "{$themePath}/functions.php";
    	}

		// Extract $oden->data to own variables and handover to the template  file.
		extract($this->data);
		extract($this->views->GetData());  
		if(isset($this->config['theme']['data'])) {
      		extract($this->config['theme']['data']);
    	}
		$templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
		if(is_file("{$themePath}/{$templateFile}")) {
		      include("{$themePath}/{$templateFile}");
		} 
		else if(is_file("{$parentPath}/{$templateFile}")) {
		      include("{$parentPath}/{$templateFile}");
		} 
		else {
		      throw new Exception('No such template file.');
		}
	}

    /**
    * Create an url. Wrapper and shorter method for $this->request->CreateUrl()
    * @param $urlOrController string the relative url or the controller
    * @param $method string the method to use, $url is then the controller or empty for current
    * @param $arguments string the extra arguments to send to the method
    */
    public function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
	  return $this->request->CreateUrl($urlOrController, $method, $arguments);
	}

	// Redirect to another url and store the session, all redirects should use this method.
	public function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
	    if(isset($this->config['debug']['db-num-queries']) && $this->config['debug']['db-num-queries'] && isset($this->db)) {
	      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
	    }    
	    if(isset($this->config['debug']['db-queries']) && $this->config['debug']['db-queries'] && isset($this->db)) {
	      $this->session->SetFlash('database_queries', $this->db->GetQueries());
	    }    
	    if(isset($this->config['debug']['timer']) && $this->config['debug']['timer']) {
	            $this->session->SetFlash('timer', $this->timer);
	    }    
	    $this->session->StoreInSession();
	    header('Location: ' . $this->request->CreateUrl($urlOrController, $method, $arguments));
	    exit;
    }

    public function RedirectToController($method=null, $arguments=null) {
   		$this->RedirectTo($this->request->controller, $method, $arguments);
    }

    public function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
        $controller = is_null($controller) ? $this->request->controller : null;
        $method = is_null($method) ? $this->request->method : null;          
    	$this->RedirectTo($this->request->CreateUrl($controller, $method, $arguments));
    }

    // Save a message in the session. Uses $this->session->AddMessage()
    public function AddMessage($type, $message, $alternative=null) {
	    if($type === false) {
	      $type = 'error';
	      $message = $alternative;
	    } else if($type === true) {
	      $type = 'success';
	    }
	    $this->session->AddMessage($type, $message);
    }

    /**
   * Draw HTML for a menu defined in $oden->config['menus'].
   * @param $menu, string then key to the menu in the config-array.
   * @return string with the HTML representing the menu.
   */
    public function DrawMenu($menu) {
	    $items = null;
	    if(isset($this->config['menus'][$menu])) {
	      foreach($this->config['menus'][$menu] as $val) {
	        $selected = null;
	        if($val['url'] == $this->request->request || $val['url'] == $this->request->routed_from) {
	          $selected = " class='selected'";
	        }
	        $items .= "<li><a {$selected} href='" . $this->request->CreateUrl($val['url']) . "'>{$val['label']}</a></li>\n";
	      }
	    } else {
	      throw new Exception('No such menu.');
	    }     
	    return "<ul class='menu {$menu}'>\n{$items}</ul>\n";
    }

}