<?php
$this->setPageTitle('Tambah Kas Keluar');
$this->breadcrumbs=array(
	'Kas Keluar'=>array('index'),
	'Tambah',
);

?>

<?php

$this->beginWidget('zii.widgets.CPortlet', array(
	'htmlOptions'=>array(
		'class'=>''
	)
));
$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
		array('visible' => landa()->checkAccess('AccCashOut', 'c'), 'label'=>'Tambah', 'icon'=>'icon-plus', 'url'=>Yii::app()->controller->createUrl('create'),'active'=>true, 'linkOptions'=>array()),
                array('visible' => landa()->checkAccess('AccCashOut', 'r'),'label'=>'Daftar', 'icon'=>'icon-th-list', 'url'=>Yii::app()->controller->createUrl('index'), 'linkOptions'=>array()),
	),
));

$this->endWidget();
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
