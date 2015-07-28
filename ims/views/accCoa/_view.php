<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->created_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified')); ?>:</b>
	<?php echo CHtml::encode($data->modified); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('level')); ?>:</b>
	<?php echo CHtml::encode($data->level); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('lft')); ?>:</b>
	<?php echo CHtml::encode($data->lft); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rgt')); ?>:</b>
	<?php echo CHtml::encode($data->rgt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('root')); ?>:</b>
	<?php echo CHtml::encode($data->root); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_id')); ?>:</b>
	<?php echo CHtml::encode($data->parent_id); ?>
	<br />

	*/ ?>

</div>