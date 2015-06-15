<div class="form">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'room-schedule-form',
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


                                    <?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'room_id',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'date_schedule',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>11)); ?>

                                        <?php echo $form->textFieldRow($model,'reservation_id',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'registration_id',array('class'=>'span5')); ?>

                    

        <div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
                        'icon'=>'ok white',  
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
            <?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'reset',
                        'icon'=>'remove',  
			'label'=>'Reset',
		)); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
