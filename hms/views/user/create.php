<?php
$this->setPageTitle('Create '.$type);
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create'.$type,
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
		 array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create',array('type'=>$type)),'active' => true, 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl($type),  'linkOptions' => array()),
        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
	),
));
$this->endWidget();
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'type'=>$type)); ?>
