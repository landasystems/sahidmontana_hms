<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_na')); ?>:</b>
	<?php echo CHtml::encode($data->date_na); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('global_by_cash')); ?>:</b>
	<?php echo CHtml::encode($data->global_by_cash); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('global_by_cc')); ?>:</b>
	<?php echo CHtml::encode($data->global_by_cc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('global_by_gl')); ?>:</b>
	<?php echo CHtml::encode($data->global_by_gl); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('global_by_cl')); ?>:</b>
	<?php echo CHtml::encode($data->global_by_cl); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('created_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->created_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified')); ?>:</b>
	<?php echo CHtml::encode($data->modified); ?>
	<br />

	*/ ?>

</div>