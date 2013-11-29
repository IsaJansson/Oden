<?php
/*
* Standard controller layout.
* @package OdenCore
*/

class CCIndex implements IController {
	// Implementing interface IController. All controllers must have a index action. 
	public function Index() {
		global $oden;
		$oden->data['title'] = "The Index Controller";
	}
}