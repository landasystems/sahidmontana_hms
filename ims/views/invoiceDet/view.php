<?php
/* @var $this InvoiceDetController */
/* @var $model InvoiceDet */

$this->breadcrumbs=array(
	'Invoice Dets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List InvoiceDet', 'url'=>array('index')),
	array('label'=>'Create InvoiceDet', 'url'=>array('create')),
	array('label'=>'Update InvoiceDet', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete InvoiceDet', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage InvoiceDet', 'url'=>array('admin')),
);
?>

<h1>View InvoiceDet #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'description',
		'user_id',
		'payment',
		'charge',
		'type',
	),
)); ?>
