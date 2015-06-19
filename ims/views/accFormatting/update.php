<?php
/* @var $this AccFormattingController */
/* @var $model AccFormatting */

$this->breadcrumbs=array(
	'Acc Formattings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AccFormatting', 'url'=>array('index')),
	array('label'=>'Create AccFormatting', 'url'=>array('create')),
	array('label'=>'View AccFormatting', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AccFormatting', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>