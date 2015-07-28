<?php
$this->setPageTitle('Edit Site Config | ID : '. $model->id);
$this->breadcrumbs=array(
	'Site Configs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

?>

<?php 
$this->beginWidget('zii.widgets.CPortlet', array(
	'htmlOptions'=>array(
		'class'=>''
	)
));

$this->endWidget();
?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>