<?php
$this->breadcrumbs=array(
	'Na Trans'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List NaTrans','url'=>array('index')),
	array('label'=>'Create NaTrans','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('na-trans-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Na Trans</h1>

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
	'id'=>'na-trans-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'na_id',
		'charge_additional_category_id',
		'name',
		'room_id',
		'by',
		/*
		'by_cc',
		'by_cl',
		'by_gl',
		'by_bank',
		'by_cash',
		'cashier_user_id',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
