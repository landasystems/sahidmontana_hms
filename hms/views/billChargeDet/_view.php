<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_charge_id')); ?>:</b>
	<?php echo CHtml::encode($data->bill_charge_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('charge_additional_id')); ?>:</b>
	<?php echo CHtml::encode($data->charge_additional_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amount')); ?>:</b>
	<?php echo CHtml::encode($data->amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('charge')); ?>:</b>
	<?php echo CHtml::encode($data->charge); ?>
	<br />


</div>