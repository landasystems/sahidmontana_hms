<?php
$this->menu=array(
	array('label'=>'List AccFormatting', 'url'=>array('index')),
	array('label'=>'Create AccFormatting', 'url'=>array('create')),
	array('label'=>'Update AccFormatting', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete AccFormatting', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AccFormatting', 'url'=>array('admin')),
);
?>

<h1>View AccFormatting #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'departement_id',
		'cash_in',
		'cash_in_approval',
		'bank_in_approval',
		'cash_out',
		'cash_out_approval',
		'bank_out_approval',
		'journal',
		'journal_approval',
	),
)); ?>
