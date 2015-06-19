<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_trans')); ?>:</b>
	<?php echo CHtml::encode($data->date_trans); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_debet')); ?>:</b>
	<?php echo CHtml::encode($data->total_debet); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_credit')); ?>:</b>
	<?php echo CHtml::encode($data->total_credit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('acc_admin_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->acc_admin_user_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('acc_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->acc_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->created_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified')); ?>:</b>
	<?php echo CHtml::encode($data->modified); ?>
	<br />

	*/ ?>

</div>