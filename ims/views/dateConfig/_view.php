<?php
/* @var $this DateConfigController */
/* @var $data DateConfig */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year')); ?>:</b>
	<?php echo CHtml::encode($data->year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cash_in')); ?>:</b>
	<?php echo CHtml::encode($data->cash_in); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cash_out')); ?>:</b>
	<?php echo CHtml::encode($data->cash_out); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bk_in')); ?>:</b>
	<?php echo CHtml::encode($data->bk_in); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bk_out')); ?>:</b>
	<?php echo CHtml::encode($data->bk_out); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('jurnal')); ?>:</b>
	<?php echo CHtml::encode($data->jurnal); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('cash_in_jkt')); ?>:</b>
	<?php echo CHtml::encode($data->cash_in_jkt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cash_out_jkt')); ?>:</b>
	<?php echo CHtml::encode($data->cash_out_jkt); ?>
	<br />

	*/ ?>

</div>