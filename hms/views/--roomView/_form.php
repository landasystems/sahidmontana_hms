<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'room-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>
        <legend>
            <p class="note">Fields with <span class="required">*</span> is Required.</p>
        </legend>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>

        <div class="control-group">		
            <div class="span4">

                <?php echo $form->textFieldRow($model, 'number', array('class' => 'span1', 'maxlength' => 3)); ?>
                <?php echo $form->dropDownListRow($model, 'room_type_id', CHtml::listData(RoomType::model()->findAll(), 'id', 'name')); ?>

                <?php echo $form->dropDownListRow($model, 'floor', array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10')); ?>
                
                <?php echo $form->dropDownListRow($model, 'status', $model->statusList); ?>
                <?php echo $form->dropDownListRow($model, 'floor', array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10')); ?>
                <?php echo $form->dropDownListRow($model, 'bed', array('SINGLE' => 'Single', 'TWIN' => 'Twin')); ?>

            </div>   
        </div>

        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'label' => $model->isNewRecord ? 'Create' : 'Save',
            ));
            ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
