<?php

/*
* Main class for Oden and it holds everything.
* @package OdenCore
*/

class COden implements ISingleton {
	private static $instance = null;

	/*
	* Contructor
	*/
	protected function __construct() {
		// include the site-specific config.php and create a reference to $oden to be used in named file.
		$oden = &$this;
		require(ODEN_SITE_PATH . '/config.php');
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
		$this->data['debug']  = "REQUEST_URI - {$_SERVER['REQUEST_URI']}\n";
    	$this->data['debug'] .= "SCRIPT_NAME - {$_SERVER['SCRIPT_NAME']}\n";
    	
		// Take current url and devide it into controller, method and arguments.
		$this->request = new CRequest();
		$this->request->Init($this->config['base_url']);
		$controller = $this->request->controller;
		$method = $this->request->method;
		$arguments = $this->request->arguments;

		// Is the controller enabled in config.php?
		$controllerExists = isset($this->config['controller'][$controller]);
		$controllerEnabled = false;
		$className = false;
		$classExists = false;

		if($controllerExists) {
			$controllerEnabled = ($this->config['controller'][$controller]['enabled'] == true);
			$className = $this->config['controller'][$controller]['class'];
			$classExists = class_exists($className);
		}

		// Check if the controller has a callable method in the controller class, if so, call it
		if($controllerExists && $controllerEnabled && $classExists) {
			$rc = new ReflectionClass($className);
			if($rc->implementsInterface('IController')) {
				if($rc->hasMethod($method)) {
					$controllerObj = $rc->newInstance();
					$methodObj = $rc->getMethod($method);
					$methodObj->invokeArgs($controllerObj, $arguments);
				}
				else {
					die("404." . get_class() . ' error: Controller does not contain method.');
				}
			}
			else {
				die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
			}
		}
		else {
			die('404. Page not found.');
		}
	}

	/* 
	* Theme engine render, renders the views using the selcted theme.
	*/
	public function ThemeEngineRender() {
		// Get the paths and settings for the theme.
		$themeName = $this->config['theme']['name'];
		$themePath = ODEN_INSTALL_PATH . "/theme/{$themeName}";
		$themeUrl = $this->request->base_url . "theme/{$themeName}";

		// Add stylesheet path to the $oden->data array
		$this->data['stylesheet'] = "{$themeUrl}/style.css";

		// Incude the global functions.php and the ones that are parts of the theme.
		$oden = &$this;
		$functionsPath = "{$themePath}/functions.php";
		if(is_file($functionsPath)) {
			include $functionsPath;
		}

		// Extract $oden->data to own variables and handover to the template  file.
		extract($this->data);
		include("{$themePath}/default.tpl.php");
	}

}