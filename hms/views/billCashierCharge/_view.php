<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_cashier_id')); ?>:</b>
	<?php echo CHtml::encode($data->bill_cashier_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_charge_id')); ?>:</b>
	<?php echo CHtml::encode($data->bill_charge_id); ?>
	<br />


</div>