<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('room_id')); ?>:</b>
	<?php echo CHtml::encode($data->room_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_schedule')); ?>:</b>
	<?php echo CHtml::encode($data->date_schedule); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reservation_id')); ?>:</b>
	<?php echo CHtml::encode($data->reservation_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('registration_id')); ?>:</b>
	<?php echo CHtml::encode($data->registration_id); ?>
	<br />


</div>