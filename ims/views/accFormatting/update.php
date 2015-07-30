<?php
$this->menu=array(
	array('label'=>'List AccFormatting', 'url'=>array('index')),
	array('label'=>'Create AccFormatting', 'url'=>array('create')),
	array('label'=>'View AccFormatting', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AccFormatting', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>