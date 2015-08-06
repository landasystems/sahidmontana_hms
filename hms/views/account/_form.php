<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'account-form',
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


        <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?>
        <?php echo $form->textFieldRow($model, 'tax', array('class' => 'span1', 'append' => '%', 'maxlength' => 255)); ?>
        <?php echo $form->textFieldRow($model, 'service', array('class' => 'span1', 'append' => '%', 'maxlength' => 255)); ?>
        <div class="control-group">
            <lable class="control-label">Chart of Account <span class="required">*</span></lable>
            <div class="controls">
                <?php
                $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                $this->widget('bootstrap.widgets.TbSelect2', array(
                    'asDropDownList' => TRUE,
                    'data' => $coa,
                    'value' => $model->acc_coa_id,
                    'name' => 'Account[acc_coa_id]',
                    'options' => array(
                        "placeholder" => 'Please Choose',
                        "allowClear" => true,
                        'width' => '40%',
                    ),
                    'htmlOptions' => array(
                        'id' => 'Account_acc_coa_id',
                    ),
                ));
                ?>
            </div>
        </div>
        <?php echo $form->textAreaRow($model, 'description', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>


        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'visible' => !isset($_GET['v']),
                'label' => $model->isNewRecord ? 'Create' : 'Save',
            ));
            ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
