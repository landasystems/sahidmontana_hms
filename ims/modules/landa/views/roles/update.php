<?php
$this->setPageTitle('Edit Roles | ID : '. $model->id);
$this->breadcrumbs=array(
	'Roles'=>array($type),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

?>

<?php 
$visible = "";
if ($type == "client") {
    $visible = landa()->checkAccess('GroupClient', 'c');
} elseif ($type == "contact") {
    $visible = landa()->checkAccess('GroupContact', 'c');
} elseif ($type == "customer") {
    $visible = landa()->checkAccess('GroupCustomer', 'c');
} elseif ($type == "employment") {
    $visible = landa()->checkAccess('GroupEmployment', 'c');
} elseif ($type == "guest") {
    $visible = landa()->checkAccess('GroupGuest', 'c');
} elseif ($type == "supplier") {
    $visible = landa()->checkAccess('GroupSupplier', 'c');
} else {
    $visible = landa()->checkAccess('GroupUser', 'c');
}
if(isset($type)){
    $sType = $type;
}else{
    $sType='index';
}
$this->beginWidget('zii.widgets.CPortlet', array(
	'htmlOptions'=>array(
		'class'=>''
	)
));
$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
		array('visible'=>$visible,'label'=>'Tambah', 'icon'=>'icon-plus', 'url'=>Yii::app()->controller->createUrl('create',array('type'=>$type)), 'linkOptions'=>array()),
                array('label'=>'Daftar', 'icon'=>'icon-th-list', 'url'=>Yii::app()->controller->createUrl($sType), 'linkOptions'=>array()),
//                array('label'=>'Edit', 'icon'=>'icon-edit', 'url'=>Yii::app()->controller->createUrl('update',array('id'=>$model->id,'type'=>$type)),'active'=>true, 'linkOptions'=>array()),
	),
));
$this->endWidget();
?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>