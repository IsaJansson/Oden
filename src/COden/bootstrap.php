<?php 

/* 
* Bootstrapping, setting up the core and loadning it.
* @package OdenCore
*/

// Enable auto-load function

function autoload($aClassName) {
	$classFile = "/src/{$aClassName}/{$aClassName}.php";
	$file1 = ODEN_SITE_PATH . $classFile;
	$file2 = ODEN_INSTALL_PATH . $classFile;
	if(is_file($file1)) {
		require_once($file1);
	}
	elseif(is_file($file2)) {
		require_once($file2);
	}
}
spl_autoload_register('autoload');