<?php
$this->setPageTitle('Lihat Site Config | ID : '. $model->id);
$this->breadcrumbs=array(
	'Site Config'=>array('index'),
	$model->id,
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
		array('label'=>'Edit', 'icon'=>'icon-edit', 'url'=>Yii::app()->controller->createUrl('update',array('id'=>$model->id)), 'linkOptions'=>array()),
		//array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
		array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),

)));
$this->endWidget();
?>
<div class='printableArea'>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'client_name',
		'client_logo',
		'city_id',
		'address',
		'phone',
		'email',
	),
)); ?>
</div>
<style type="text/css" media="print">
body {visibility:hidden;}
.printableArea{visibility:visible;} 
</style>
<script type="text/javascript">
function printDiv()
{

window.print();

}
</script>
