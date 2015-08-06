<div class="form">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'bill-ca-form',
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

                                        <?php echo $form->textFieldRow($model,'bill_id',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'bill_charge_id',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'charge',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'charge_less',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'description',array('class'=>'span5','maxlength'=>255)); ?>

                                        <?php echo $form->textFieldRow($model,'created',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'created_user_id',array('class'=>'span5')); ?>

                                        <?php echo $form->textFieldRow($model,'modified',array('class'=>'span5')); ?>

                    

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
