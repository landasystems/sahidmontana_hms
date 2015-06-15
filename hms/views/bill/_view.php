<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->created_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cash')); ?>:</b>
	<?php echo CHtml::encode($data->cash); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('charge')); ?>:</b>
	<?php echo CHtml::encode($data->charge); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ca_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->ca_user_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('refund')); ?>:</b>
	<?php echo CHtml::encode($data->refund); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total')); ?>:</b>
	<?php echo CHtml::encode($data->total); ?>
	<br />

	*/ ?>

</div>