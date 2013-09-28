<?php

//
// PHASE: BOOTSTRAP
//

define('MALIN_INSTALL_PATH', dirname(__FILE__));
define('MALIN_SITE_PATH', MALIN_INSTALL_PATH . '/site');

require(MALIN_INSTALL_PATH.'/src/CMalin/bootstrap.php');

$ma = CMalin::Instance();

//
// PHASE: FRONTCONTROLLER ROUTE
//

$ma->FrontControllerRoute();

//
// PHASE: THEME ENGINE RENDER
//

$ma->ThemeEngineRender();
