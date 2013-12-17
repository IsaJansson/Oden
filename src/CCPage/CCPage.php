<?php
/**
* A page controller to display pages. Displays content labled as "page"
* @package OdenCore
*/

class CCPage extends CObject implements IController {
	
	// Constructor 
	public function __construct() {
		parent::__construct();
	}

	// Display an empty page 
	public function Index() {
		$content = new CMContent();
    	$this->views->SetTitle('Page')
                	->AddInclude(__DIR__ . '/index.tpl.php', array(
                  	'content' => null,
                ));
  }

	// Display a page 
	public function View($id=null) {
		 $content = new CMContent($id);
	     $this->views->SetTitle('Page: '.htmlEnt($content['title']))
	                 ->AddInclude(__DIR__ . '/index.tpl.php', array(
	                 'content' => $content,
	                 ));
    }

}