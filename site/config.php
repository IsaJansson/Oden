<?php

/* 
* Site configuration file, this file is changed for each site.
* Error reporting 
*/
error_Reporting(-1);
ini_set('display_errors', 1);

$oden->config['debug']['session'] = false;
$oden->config['debug']['timer'] = true;
$oden->config['debug']['oden'] = false;
$oden->config['debug']['db-num-queries'] = true;
$oden->config['debug']['db-queries'] = true;

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

// How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
$oden->config['hashing_algorithm'] = 'sha1salt';

// Allow or disallow creation of new user accounts.
$oden->config['create_new_users'] = true;

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
  'user'	    => array('enabled' => true,'class' => 'CCUser'),
  'acp'		    => array('enabled' => true,'class' => 'CCAdminControlPanel'),
  'content'	  => array('enabled' => true,'class' => 'CCContent'),
  'blog'	    => array('enabled' => true,'class' => 'CCBlog'),	
  'page'	    => array('enabled' => true,'class' => 'CCPage'),
  'theme'     => array('enabled' => true,'class' => 'CCTheme'),
  'module'    => array('enabled' => true,'class' => 'CCModules'),
  'my'        => array('enabled' => true,'class' => 'CCMyController'),
  );

// Define a coustom url to a controller/method/argument
$oden->config['routing'] = array(
  'home' => array('enabled' => true, 'url' => 'index/index'),
  );
 
// Oden menu 
$oden->config['menus'] = array(
  'navbar' => array(
    'home'      => array('label'=>'Home', 'url'=>'home'),
    'modules'   => array('label'=>'Modules', 'url'=>'module'),
    'content'   => array('label'=>'Content', 'url'=>'content'),
    'guestbook' => array('label'=>'Guestbook', 'url'=>'guestbook'),
    'blog'      => array('label'=>'Blog', 'url'=>'blog'),
  ),
  'my-navbar' => array(
    'home'      => array('label'=>'About Me', 'url'=>'my'),
    'blog'      => array('label'=>'My Blog', 'url'=>'my/blog'),
    'guestbook' => array('label'=>'Guestbook', 'url'=>'my/guestbook'),
  ),
);


// Settings for the theme
$oden->config['theme'] = array(
  'path'            => 'site/themes/mytheme', 
  //'path'          => 'theme/grid',
  'parent'          => 'theme/grid',
  'stylesheet'      => 'style.css',           // Main stylesheet to include in template files
  'template_file'   => 'index.tpl.php',       // Default template file, else use default.tpl.php
  // A list of valid theme regions
  'regions' => array(
    'navbar', 'flash','featured-first','featured-middle','featured-last',
    'primary','sidebar','triptych-first','triptych-middle','triptych-last',
    'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
    'footer',
  ),
   'menu_to_region' => array('my-navbar'=>'navbar'),
  // Add static entries for use in the template file 
  'data' => array(
    'header' => 'Hi I\'m Oden',
    'slogan' => 'A PHP-based MVC-inspired CMF',
    'favicon' => '/img/logo.png',
    'logo' => '/img/logo.png',
    'logo_width' => 100,
    'logo_heigth' => 80,
    'footer' => "<p>Oden &copy; By Isa Jansson</p><p><a href='../index.php'>Min me-sida</a></p>",
    ),
);


