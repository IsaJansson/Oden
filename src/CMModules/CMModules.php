<?php

/** 
* A model for managing Oden modules 
* @package OdenCore
*/

class CMModules extends CObject {
	
	// Constructor 
	public function __construct() {parent::__construct();}

	/** 
	* A list of avaliable controllers/methods 
	* @return array of list controllers and methods 
	*/
	public function AvaliableControllers() {
		$controllers = array();
		foreach($this->config['controllers'] as $key => $val) {
			if($val['enabled']) {
				$rc = new ReflectionClass($val['class']);
				$controllers[$key] = array();
				$methods = $rc->GetMethods(ReflectionMethod::IS_PUBLIC);
				foreach($methods as $method) {
					if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'index') {
						$methodName = mb_strtolower($method->name);
					}
				}
				sort($controllers[$key], SORT_LOCALE_STRING);
			}
		}
		ksort($controllers, SORT_LOCALE_STRING);
		return $controllers;
	}

	/**
	 * Read and analyse all modules 
	 * @return array with a entry for each module if it can be opend else false
	 */
	public function ReadAndAnalyse() {
		$src = ODEN_INSTALL_PATH . '/src';
		if(!$dir = dir($src)) throw new Exception('Could not open the directory.');
		$modules = array();
		while (($module = $dir->read()) !== false) {
			if(is_dir("$src/$module")) {
				if(class_exists($module)) {
					$rc = new ReflectionClass($module);
					$modules[$module]['name']			= $rc->name;
					$modules[$module]['interface']		= $rc->getInterfaceNames();
					$modules[$module]['isController']	= $rc->implementsInterface('IController');
					$modules[$module]['isModel']		= preg_match('/^CM[A-Z]/', $rc->name);
					$modules[$module]['hasSQL']			= $rc->implementsInterface('IHasSQL');
					$modules[$module]['isOdenCore']		= in_array($rc->name, array('COden', 'CDatabase', 'CRequest', 'CViewContainer', 'CSession', 'CObject'));
					$modules[$module]['isOdenCMF']		= in_array($rc->name, array('CForm', 'CCPage', 'CCBlog', 'CMUser', 'CCUser', 'CMContent', ' CCContent', 'CFormUserLogin', 'CFormUserProfile', ' CFormUserCreate', 'CFormContent', 'CHTMLPurifier'));
					$modules[$module]['isManageable']   = $rc->implementsInterface('IModule');
				}
			}
		}
		$dir->close();
		ksort($modules, SORT_LOCALE_STRING);
		return $modules;
	}

		/**
	 * Install all modules 
	 * @return array with an entry for each mosule and the result from installing it
	 */
	public function Install() {
	    $allModules = $this->ReadAndAnalyse();
	    uksort($allModules, function($a, $b) {
	        return ($a == 'CMUser' ? -1 : ($b == 'CMUser' ? 1 : 0));
	      }
	    );
	    $installed = array();
	    foreach($allModules as $module) {
	      if($module['isManageable']) {
	        $classname = $module['name'];
	        $rc = new ReflectionClass($classname);
	        $obj = $rc->newInstance();
	        $method = $rc->getMethod('Manage');
	        $installed[$classname]['name']    = $classname;
	        $installed[$classname]['result']  = $method->invoke($obj, 'install');
	      }
	    }
	    //ksort($installed, SORT_LOCALE_STRING);
	    return $installed;
	}

}