<?php

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

function logs($text) {
    Yii::log('Landa Var Dump', CLogger::LEVEL_ERROR, CVarDumper::dumpAsString($text));
}

function trace($text) {
    Yii::trace(CVarDumper::dumpAsString($text), 'Landa Var Dump');
}

/**
 * This is the shortcut to Yii::app()
 */
function landa() {
    return Yii::app()->landa;
}

/**
 * This is the shortcut to Yii::app()
 */
function app() {
    return Yii::app();
}

/**
 * This is the shortcut to Yii::app()->clientScript
 */
function cs() {
    // You could also call the client script instance via Yii::app()->clientScript
    // But this is faster
    return Yii::app()->getClientScript();
}

/**
 * This is the shortcut to Yii::app()->user.
 */
function user() {
    return Yii::app()->getUser();
}

/**
 * This is the shortcut to Yii::app()->createUrl()
 */
function url($route, $params = array(), $ampersand = '&') {
//    trace($route);
    return Yii::app()->createUrl($route, $params, $ampersand);
}

/**
 * This is the shortcut to CHtml::encode
 */
function h($text) {
    return htmlspecialchars($text, ENT_QUOTES, Yii::app()->charset);
}

/**
 * This is the shortcut to CHtml::link()
 */
function l($text, $url = '#', $htmlOptions = array()) {
    return CHtml::link($text, $url, $htmlOptions);
}

/**
 * This is the shortcut to Yii::t() with default category = 'stay'
 */
function t($message, $category = 'stay', $params = array(), $source = null, $language = null) {
    return Yii::t($category, $message, $params, $source, $language);
}

/**
 * This is the shortcut to Yii::app()->request->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 */
function bu($url = null, $absolute = false) {
    static $baseUrl;
    if ($baseUrl === null)
        $baseUrl = Yii::app()->getRequest()->getBaseUrl($absolute);
    return $url === null ? $baseUrl : $baseUrl . '/' . ltrim($url, '/');
}

function param($name) {
    return Yii::app()->params[$name];
}

function cmd($txt='') {
    return Yii::app()->db->createCommand($txt);
}

function session(){
    $session = new CHttpSession;
    $session->open();
    return $session;
}
?>