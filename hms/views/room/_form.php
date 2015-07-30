<div class="form">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'room-form',
	'enableAjaxValidation'=>false,
        'method'=>'post',
	'type'=>'horizontal',
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data'
	)
)); ?>
    <fieldset>
        <legend>
            <p class="note">Fields with <span class="required">*</span> is Required.</p>
        </legend>

        <?php echo $form->errorSummary($model,'Opps!!!', null,array('class'=>'alert alert-error span12')); ?>


                                    <?php echo $form->textFieldRow($model,'number',array('class'=>'span5','maxlength'=>3)); ?>

                                        
        
                                        <?php echo $form->dropDownListRow($model, 'room_type_id', CHtml::listData(RoomType::model()->findAll(array('condition'=>'is_package=0')), 'id', 'name'), array('class' => 'span3')); ?>

                                       <?php echo $form->dropDownListRow($model, 'floor', Room::model()->floorList); ?>

                                        <?php echo $form->dropDownListRow($model, 'bed', Room::model()->bedList); ?>                                        

                                        <?php //echo $form->textFieldRow($model,'linked_room_id',array('class'=>'span5')); ?>

                    

        <div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
                        'icon'=>'ok white',  
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
