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

// Helper, wrap html_entites with correct character encoding
function htmlent($str, $flags = ENT_COMPAT) {
  return htmlentities($str, $flags, COden::Instance()->config['character_encoding']);
}

// Set a default exception handler and enable logging in it.
function exception_handler($exception) {
  echo "Oden: Uncaught exception: <p>" . $exception->getMessage() . "</p><pre>" . $exception->getTraceAsString(), "</pre>";
}
set_exception_handler('exception_handler');