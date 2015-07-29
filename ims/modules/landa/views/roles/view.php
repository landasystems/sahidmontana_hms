<?php
$this->setPageTitle('Lihat Roles | ID : '. $model->id);
$this->breadcrumbs=array(
	'Roles'=>array($type),
	$model->name,
);
?>

<?php
$create = "";
$edit = "";
if ($type == "customer") {
    $create = landa()->checkAccess('GroupCustomer', 'c');
    $edit = landa()->checkAccess('GroupCustomer', 'u');
} elseif ($type == "supplier") {
    $create = landa()->checkAccess('GroupSupplier', 'c');
    $edit = landa()->checkAccess('GroupSupplier', 'u');
} elseif ($type == "employment") {
    $create = landa()->checkAccess('GroupEmployment', 'c');
    $edit = landa()->checkAccess('GroupEmployment', 'u');
} elseif ($type == "guest") {
    $create = landa()->checkAccess('GroupGuest', 'c');
    $edit = landa()->checkAccess('GroupGuest', 'u');
} elseif ($type == "client") {
    $create = landa()->checkAccess('GroupClient', 'c');
    $edit = landa()->checkAccess('GroupClient', 'u');
} elseif ($type == "contact") {
    $create = landa()->checkAccess('GroupContact', 'c');
    $edit = landa()->checkAccess('GroupContact', 'u');
} else {
    $create = landa()->checkAccess('GroupUser', 'c');
    $edit = landa()->checkAccess('GroupUser', 'u');
}
$stype = ($type == 'user') ? 'index' : $type;
$this->beginWidget('zii.widgets.CPortlet', array(
	'htmlOptions'=>array(
		'class'=>''
	)
));
$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
		array('visible'=>$create,'label'=>'Tambah', 'icon'=>'icon-plus', 'url'=>Yii::app()->controller->createUrl('create', array('type' => $type)), 'linkOptions'=>array()),
                array('label'=>'Daftar', 'icon'=>'icon-th-list', 'url'=>Yii::app()->controller->createUrl($stype), 'linkOptions'=>array()),
                array('visible'=>$edit,'label'=>'Edit', 'icon'=>'icon-edit', 'url'=>Yii::app()->controller->createUrl('update',array('id'=>$model->id,'type' => $type)), 'linkOptions'=>array()),
		//array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
		array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),

)));
$this->endWidget();
?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>