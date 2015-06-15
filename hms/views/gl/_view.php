<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('na_id')); ?>:</b>
	<?php echo CHtml::encode($data->na_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('room_id')); ?>:</b>
	<?php echo CHtml::encode($data->room_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('guest_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->guest_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('charge_previous')); ?>:</b>
	<?php echo CHtml::encode($data->charge_previous); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('charge')); ?>:</b>
	<?php echo CHtml::encode($data->charge); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('charge_settle')); ?>:</b>
	<?php echo CHtml::encode($data->charge_settle); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('charge_balance')); ?>:</b>
	<?php echo CHtml::encode($data->charge_balance); ?>
	<br />

	*/ ?>

</div>