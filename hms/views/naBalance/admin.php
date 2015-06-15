<?php
$this->breadcrumbs=array(
	'Na Balances'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List NaBalance','url'=>array('index')),
	array('label'=>'Create NaBalance','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('na-balance-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Na Balances</h1>

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
	'id'=>'na-balance-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'na_id',
		'name',
		'by_cash',
		'by_cc',
		'by_bank',
		/*
		'by_gl',
		'by_cl',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
