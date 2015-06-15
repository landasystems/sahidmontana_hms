<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('guest_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->guest_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->created_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reserved_by')); ?>:</b>
	<?php echo CHtml::encode($data->reserved_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cp_name')); ?>:</b>
	<?php echo CHtml::encode($data->cp_name); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('cp_telephone_number')); ?>:</b>
	<?php echo CHtml::encode($data->cp_telephone_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cp_note')); ?>:</b>
	<?php echo CHtml::encode($data->cp_note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_from')); ?>:</b>
	<?php echo CHtml::encode($data->date_from); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_to')); ?>:</b>
	<?php echo CHtml::encode($data->date_to); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('billing_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->billing_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('billing_note')); ?>:</b>
	<?php echo CHtml::encode($data->billing_note); ?>
	<br />

	*/ ?>

</div>