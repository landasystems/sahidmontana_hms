<?php
$this->setPageTitle('Edit Site Config');
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