<?php

$this->setPageTitle($model->title);
//trace($_GET['alias']);
$this->breadcrumbs = array(
//    'Articles' => array('index'),
    $model->title,
);


echo $model->content;

?>


