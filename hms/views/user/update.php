<?php
$this->setPageTitle('Users | : '. $model->name);

?>

<?php 
$this->beginWidget('zii.widgets.CPortlet', array(
	'htmlOptions'=>array(
		'class'=>''
	)
));

$this->endWidget();
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
