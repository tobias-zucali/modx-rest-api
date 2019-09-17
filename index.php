<?php
// allow any access as it is read only
header("Access-Control-Allow-Origin: *");

$MODX_CORE_DIRECTORY = dirname(__FILE__) . '/../modx-git/core/';

// Boot up MODX
require_once $MODX_CORE_DIRECTORY . 'config/config.inc.php';
require_once $MODX_CORE_DIRECTORY . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');
$modx->getService('error','error.modError', '', '');
// Boot up any service classes or packages (models) you will need
$modx->addPackage('myPage', $MODX_CORE_DIRECTORY . 'model/');

// Load the modRestService class and pass it some basic configuration
$rest = $modx->getService('rest', 'rest.modRestService', '', array(
    'basePath' => dirname(__FILE__) . '/Controllers/',
    'controllerClassSeparator' => '',
    'controllerClassPrefix' => 'MyController',
    'xmlRootNode' => 'response',
));
// Prepare the request
$rest->prepare();
// Make sure the user has the proper permissions, send the user a 401 error if not
if (!$rest->checkPermissions()) {
    $rest->sendUnauthorized(true);
}
// Run the request
$rest->process();
