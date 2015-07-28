<?php
Yii::setPathOfAlias('', $root);
Yii::setPathOfAlias('common', $root . DIRECTORY_SEPARATOR . 'common');

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Hotel Management Systems',
    'language' => 'en',
    // preloading 'log' component
    'preload' => array('log', 'bootstrap'),
    // autoloading model band component classes
    'import' => array(
        'application.models.*',
        'common.models.*',
        'common.components.*',
        'common.extensions.*',
        'common.extensions.image.helpers.*',
    ),
    'aliases' => array(
        'xupload' => 'common.extensions.xupload'
    ),
    'modules' => array(
//        $this->layout = Yii::app()->themeManager->basePath . '/spr/views/layouts/main';
//        'rights' => array(
//            'superuserName'=>'Super User',
//            'appLayout' => 'common.themes.backend.spr.views.layouts.main',
//            'debug' => true,
//        ),
        'landa',
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'landak',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1', '192.168.1.90','192.168.1.41'),
            'generatorPaths' => array(
                'common.extensions.giiplus'  //Ajax Crud template path
            ),
        ),
    ),
    // application components
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=' . $db,
            'emulatePrepare' => true,
            'username' => $dbUser,
            'password' => $dbPwd,
            'tablePrefix' => 'acca_',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true
        ),
//        'db2' => array(
//            'connectionString' => 'mysql:host=localhost;dbname=landa_acca',
//            'emulatePrepare' => true,
//            'username' => $dbUser,
//            'password' => $dbPwd,
//            'tablePrefix' => 'intern_',
//            'charset' => 'utf8',
//            'enableProfiling' => true,
//            'enableParamLogging' => true,
//            'class' => 'CDbConnection'          // DO NOT FORGET THIS!
//        ),
        'db2' => array(
            'connectionString' => 'mysql:host=localhost;dbname='.$db2,
            'emulatePrepare' => true,
            'username' => $dbUser,
            'password' => $dbPwd,
            'tablePrefix' => 'acca_',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
            'class' => 'CDbConnection'          // DO NOT FORGET THIS!
        ),
        'landa' => array(
            'class' => 'LandaCore',
        ),
        'messages' => array(
            'basePath' => $root . 'common/messages/',
        ),
        'user' => array(
            // enable cookie-based authentication
//            'class' => 'RWebUser',
            // enable cookie-based authentication
            'loginUrl' => array('/site/login'),
            'allowAutoLogin' => true,
        ),
//        'authManager' => array(
//            'class' => 'RDbAuthManager',
////            'connectionID' => 'db',
//            'itemTable' => 'acca_rights_authitem',
//            'itemChildTable' => 'acca_rights_authitem_child',
//            'assignmentTable' => 'acca_rights_auth_assignment',
//            'rightsTable' => 'acca_rights',
//            'defaultRoles' => array('Guest'),
//        ),
//        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                // REST patterns
//                array('api/list', 'pattern' => 'api/<model:\w+>', 'verb' => 'GET'),
//                array('api/view', 'pattern' => 'api/<model:\w+>/<id:\d+>', 'verb' => 'GET'),
//                array('api/update', 'pattern' => 'api/<model:\w+>/<id:\d+>', 'verb' => 'PUT'),
//                array('api/delete', 'pattern' => 'api/<model:\w+>/<id:\d+>', 'verb' => 'DELETE'),
//                array('api/create', 'pattern' => 'api/<model:\w+>', 'verb' => 'POST'),
                'dashboard' => '/site',
//                array(
//                    'class' => 'common.components.UrlRule',
//                    'connectionID' => 'db',
//                ),
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
            'urlSuffix' => '.html',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'common.extensions.yii-debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters' => array('127.0.0.1', '192.168.1.90'),
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
        'bootstrap' => array(
            'class' => 'common.extensions.bootstrap.components.Bootstrap',
            'responsiveCss' => true,
            'fontAwesomeCss' => true,
            'enableBootboxJS'=>false,
            'enableNotifierJS'=>false,
        ),
        'image' => array(
            'class' => 'common.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver' => 'GD',
            // ImageMagick setup path
            'params' => array('directory' => '/opt/local/bin'),
        ),
        'themeManager' => array(
            'basePath' => $root . 'common/themes/backend/',
            'baseUrl' => $themesUrl . 'backend/', //this is the important part, setup a subdomain just for your common dir
        ),
        'cache' => array(
            //'class'=>'system.caching.CMemCache',
            'class' => 'system.caching.CFileCache'
        ),
//        'clientScript' => array(
//            'coreScriptPosition' => CClientScript::POS_END,
//        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
//    'params'=>require(dirname(__FILE__).'/params.php'),
    'params' => array(
        'appVersion'=>'v.1',
        'client'=>$client,
        'clientName'=>$clientName,
        'id' => '1',
        'urlImg' => $rootUrl . 'images/',
        'pathImg' => (isset($pathImg)) ? $pathImg : $root . 'hms/www/' . $client . '/images/',
//        'pathImg' => $root . 'hms/www/'.$client.'/images/',
        'menu' => $menu,
    ),
);
?>
