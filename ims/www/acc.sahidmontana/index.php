<?php
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// change the following paths if necessary
require_once(dirname(__FILE__).'/../../../common/config/acc.sahidmontana.php'); // change this line for configuration
require_once(dirname(__FILE__) . '/../../../common/globals.php');
require_once(dirname(__FILE__).'/../../../common/lib/yii/yii.php');

$config_app=require(dirname(__FILE__).'/../../config/main.php'); // change this line for configuration
Yii::createWebApplication($config_app)->run();
?>
