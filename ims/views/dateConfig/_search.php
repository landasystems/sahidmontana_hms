<?php
/* @var $this DateConfigController */
/* @var $model DateConfig */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'year'); ?>
		<?php echo $form->textField($model,'year'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cash_in'); ?>
		<?php echo $form->textField($model,'cash_in'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cash_out'); ?>
		<?php echo $form->textField($model,'cash_out'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bk_in'); ?>
		<?php echo $form->textField($model,'bk_in'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bk_out'); ?>
		<?php echo $form->textField($model,'bk_out'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jurnal'); ?>
		<?php echo $form->textField($model,'jurnal'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cash_in_jkt'); ?>
		<?php echo $form->textField($model,'cash_in_jkt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cash_out_jkt'); ?>
		<?php echo $form->textField($model,'cash_out_jkt'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->