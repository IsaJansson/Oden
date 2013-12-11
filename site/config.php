<?php

/* 
* Site configuration file, this file is changed for each site.
*/

// Error reporting 
error_Reporting(-1);
ini_set('display_errors', 1);

$oden->config['debug']['session'] = false;
$oden->config['debug']['timer'] = false;
$oden->config['debug']['oden'] = false;
$oden->config['debug']['db-num-queries'] = false;
$oden->config['debug']['db-queries'] = false;
$oden->config['debug']['timestamp'] = false;
$oden->config['debug']['memory'] = false;

// Set database(s).
$oden->config['database'][0]['dsn'] = 'sqlite:' . ODEN_SITE_PATH . '/data/.ht.sqlite';

/*
* What type of urls should be used?
* default 		= 0 => index.php/controller/method/arg1/arg2/arg3
* clean 		= 1 => controller/method/arg1/arg2/arg3
* querystring 	= 2 => index.php?q=controller/method/arg1/arg2/arg3
*/
$oden->config['url_type'] = 1;

// Set a base_url to use other then the default
// static_url = Base url for cookie-less domain for all static assets, like images, css and js files.
$oden->config['base_url'] = null;
$oden->config['static_url'] = null;

// How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
$oden->config['hashing_algorithm'] = 'sha1salt';

// Allow or disallow creation of new user accounts. 
// Set to false to disable that anyone can create a new user, set true to enable.
$oden->config['create_new_users'] = false;

/**
 * Create a secret key and use it for remote management. Make it long through sha1 or
 * similair. Disable it by setting to null/false.
 *
 * Currentoden supporting:
 * module/action/crontab/[secret_key]
 */
$oden->config['secret_key'] = false;

// Use gzip output handler to reduce size of resulting page.
$oden->config['themerender_buffer'] = false;
$oden->config['themerender_gzip'] = false;

// Settings for caching.
//$oden->config['cache']['age_default'] = 60*60*7;  // Default age for all cachable objects
//$oden->config['cache']['age_override'] = 60*60*7; // Give all cachable objects this age, use 0 to skip cache.

// Define session name 
$oden->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);
$oden->config['session_key'] = 'oden';

// Define server timezone 
$oden->config['timezone'] = 'Europe/Stockholm';

// Define internal character encoding 
$oden->config ['character_encoding'] = 'UTF-8';

// Define language 
$oden->config['language'] = 'en';


 /*
 * Define the controllers, their classname and enable/disable them.
 *
 * The array-key is matched against the url, for example: 
 * the url 'developer/dump' would instantiate the controller with the key "developer", that is 
 * CCDeveloper and call the method "dump" in that class. This process is managed in:
 * $oden->FrontControllerRoute();
 * which is called in the frontcontroller phase from index.php.
 */
$oden->config['controllers'] = array(
  'index'     => array('enabled' => true,'class' => 'CCIndex'),
  'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
  'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
  'user'	=> array('enabled' => true, 'class' => 'CCUser'),
);

// Settings for the theme
$oden->config['theme'] = array(
  // The name of the theme in the theme directory
  'name'    => 'core', 
);

