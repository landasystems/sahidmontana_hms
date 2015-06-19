<?php
/* @var $this DateConfigController */
/* @var $model DateConfig */

$this->breadcrumbs=array(
	'Date Configs'=>array('index'),
	$model->id,
);

$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'active' => true, 'linkOptions' => array()),
    ),
));
$this->endWidget();
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'year',
		'cash_in',
		'cash_out',
		'bk_in',
		'bk_out',
		'jurnal',
		'cash_in_jkt',
		'cash_out_jkt',
	),
)); ?>
