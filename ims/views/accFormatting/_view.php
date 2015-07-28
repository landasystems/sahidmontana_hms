<?php
/* @var $this AccFormattingController */
/* @var $data AccFormatting */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('departement_id')); ?>:</b>
	<?php echo CHtml::encode($data->departement_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cash_in')); ?>:</b>
	<?php echo CHtml::encode($data->cash_in); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cash_in_approval')); ?>:</b>
	<?php echo CHtml::encode($data->cash_in_approval); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_in_approval')); ?>:</b>
	<?php echo CHtml::encode($data->bank_in_approval); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cash_out')); ?>:</b>
	<?php echo CHtml::encode($data->cash_out); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cash_out_approval')); ?>:</b>
	<?php echo CHtml::encode($data->cash_out_approval); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_out_approval')); ?>:</b>
	<?php echo CHtml::encode($data->bank_out_approval); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('journal')); ?>:</b>
	<?php echo CHtml::encode($data->journal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('journal_approval')); ?>:</b>
	<?php echo CHtml::encode($data->journal_approval); ?>
	<br />

	*/ ?>

</div>