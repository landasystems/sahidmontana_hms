<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number')); ?>:</b>
	<?php echo CHtml::encode($data->number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('room_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->room_type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('floor')); ?>:</b>
	<?php echo CHtml::encode($data->floor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bed')); ?>:</b>
	<?php echo CHtml::encode($data->bed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('linked_room_id')); ?>:</b>
	<?php echo CHtml::encode($data->linked_room_id); ?>
	<br />


</div>