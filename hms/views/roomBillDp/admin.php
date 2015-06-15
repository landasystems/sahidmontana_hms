<?php
$this->breadcrumbs=array(
	'Room Bill Dps'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List RoomBillDp','url'=>array('index')),
	array('label'=>'Create RoomBillDp','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('room-bill-dp-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Room Bill Dps</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'room-bill-dp-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'room_bill_id',
		'amount',
		'cc_number',
		'created',
		'created_user_id',
		/*
		'modified',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
