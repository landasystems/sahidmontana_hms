<?php
$this->setPageTitle('Edit Users | Nama : '. $model->name);
?>

<?php 
$this->beginWidget('zii.widgets.CPortlet', array(
	'htmlOptions'=>array(
		'class'=>''
	)
));

$this->endWidget();
?>

<?php echo $this->renderPartial('_form',array('model'=>$model,'type'=>$type)); ?>
