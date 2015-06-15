<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('na_id')); ?>:</b>
	<?php echo CHtml::encode($data->na_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('by_cash')); ?>:</b>
	<?php echo CHtml::encode($data->by_cash); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('by_cc')); ?>:</b>
	<?php echo CHtml::encode($data->by_cc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('by_bank')); ?>:</b>
	<?php echo CHtml::encode($data->by_bank); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('by_gl')); ?>:</b>
	<?php echo CHtml::encode($data->by_gl); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('by_cl')); ?>:</b>
	<?php echo CHtml::encode($data->by_cl); ?>
	<br />

	*/ ?>

</div>