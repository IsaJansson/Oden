<?php

// PHASE: Bootstrap

define('ODEN_INSTALL_PATH', dirname(__FILE__));
define('ODEN_SITE_PATH', ODEN_INSTALL_PATH . '/site');

require(ODEN_INSTALL_PATH . '/src/COden/bootstrap.php');

$oden = COden::Instance();

// PHASE: Frontcontroller route

$oden->FrontControllerRoute();

// PHASE: Theme engine render

$oden->ThemeEngineRender();