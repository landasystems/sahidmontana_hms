<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reservation_id')); ?>:</b>
	<?php echo CHtml::encode($data->reservation_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('guest_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->guest_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->created_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('billing')); ?>:</b>
	<?php echo CHtml::encode($data->billing); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('col')); ?>:</b>
	<?php echo CHtml::encode($data->col); ?>
	<br />

	*/ ?>

</div>