<?php

/* 
* Site configuration file, this file is changed for each site.
*/

// Error reporting 
error_Reporting(-1);
ini_set('display_errors', 1);

// Define session name 
$oden->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);

// Define server timezone 
$oden->config['timezone'] = 'Europe/Stockholm';

// Define internal character encoding 
$oden->config ['character_encoding'] = 'UTF-8';

// Define language 
$oden->config['language'] = 'en';

$oden->config['controller'] = array(
	'index' => array('enabled' => true, 'class' => 'CCIndex'), );
 
// Setting for the theme.
$oden->config['theme'] = array(
	'name' => 'core', );

// Set a base_url to use other then the default
$oden->config['base_url'] = null;

/*
* What type of urls should be used?
* default 		= 0 => index.php/controller/method/arg1/arg2/arg3
* clean 		= 1 => controller/method/arg1/arg2/arg3
* querystring 	= 2 => index.php?q=controller/method/arg1/arg2/arg3
*/
$oden->config['url_type'] = 1;


