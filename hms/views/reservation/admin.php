<?php
$this->breadcrumbs=array(
	'Reservations'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Reservation','url'=>array('index')),
	array('label'=>'Create Reservation','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('reservation-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Reservations</h1>

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
	'id'=>'reservation-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'code',
		'guest_user_id',
		'created',
		'created_user_id',
		'reserved_by',
		/*
		'cp_name',
		'cp_telephone_number',
		'cp_note',
		'date_from',
		'date_to',
		'billing_user_id',
		'billing_note',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
