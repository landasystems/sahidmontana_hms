<?php
$this->setPageTitle('Manual Book : '. $model->title);
//$this->setPageTitle($model->title);
//trace($_GET['alias']);
$this->breadcrumbs = array(
    'Help' => array('index'),
    $model->title,
);


echo $model->content;

?>


