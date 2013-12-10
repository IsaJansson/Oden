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
		$this->Menu();
	}

	// Create a method that shows the menu, same for all methods
    private function Menu() {        
        $menu = array('index', 'index/index', 'developer', 'developer/index', 'developer/links', 'developer/display-object');
                
        $html = null;
        foreach($menu as $val) {
            $html .= "<li><a href='" . $this->request->CreateUrl($val) . "'>$val</a>";  
        }
                
        $this->data['title'] = "The Index Controller";
        $this->data['main'] = <<<EOD
		<h1>The Index Controller</h1>
		<p>This is what you can do for now:</p>
		<ul>
		$html
		</ul>
EOD;
  }
}