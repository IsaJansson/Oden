<?php
/**
* Standard controller layout.
* @package OdenCore
*/

class CCIndex extends CObject implements IController {

	// Construct 
	public function __construct() {
		parent::__construct();
	}

	// Implementing interface IController. All controllers must have a index action. 
	public function Index() {
		$this->views->SetTitle('Index Controller');
    	$this->views->AddInclude(__DIR__ . '/index.tpl.php', array('menu'=>$this->Menu()));
	}

	// Create a method that shows the menu, same for all methods
    private function Menu() {        
    	$items = array();
    	foreach($this->config['controllers'] as $key => $val) {
    		if($val['enabled']) {
    			$rc = new ReflectionClass($val['class']);
    			$items[] = $key;
    			$methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
    			foreach($methods as $method) {
    				if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'index') {
    					$items[] = "$key/" . mb_strtolower($method->name);
    				}
    			}
    		}
    	}
    	return $items;
    }


}