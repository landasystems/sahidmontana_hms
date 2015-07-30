<?php
$this->setPageTitle('Tambah Roles');
$this->breadcrumbs=array(
	'Roles'=>array($type),
	'Create',
);

?>

<?php 
if(isset($type)){
    $sType = $type;
}else{
    $sType='';
}
$this->beginWidget('zii.widgets.CPortlet', array(
	'htmlOptions'=>array(
		'class'=>''
	)
));
$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
		array('label'=>'Tambah', 'icon'=>'icon-plus', 'url'=>Yii::app()->controller->createUrl('create'),'active'=>true, 'linkOptions'=>array()),
                array('label'=>'Daftar', 'icon'=>'icon-th-list', 'url'=>Yii::app()->controller->createUrl($sType), 'linkOptions'=>array()),
	),
));
$this->endWidget();

?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>