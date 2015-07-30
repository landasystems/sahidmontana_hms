<?php
$this->menu=array(
	array('label'=>'List AccFormatting', 'url'=>array('index')),
	array('label'=>'Manage AccFormatting', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>