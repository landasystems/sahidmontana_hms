<?php
/* @var $this AccFormattingController */
/* @var $model AccFormatting */

$this->breadcrumbs=array(
	'Acc Formattings'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List AccFormatting', 'url'=>array('index')),
	array('label'=>'Create AccFormatting', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#acc-formatting-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Acc Formattings</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'acc-formatting-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'departement_id',
		'cash_in',
		'cash_in_approval',
		'bank_in_approval',
		'cash_out',
		/*
		'cash_out_approval',
		'bank_out_approval',
		'journal',
		'journal_approval',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
