<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'charge-additional-form',
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


        <?php //echo $form->textFieldRow($model, 'charge_additional_category_id', array('class' => 'span5')); ?>

        <?php echo $form->textFieldRow($model, 'name', array('class' => 'span4', 'maxlength' => 45)); ?>

        <?php echo $form->dropDownListRow($model, 'charge_additional_category_id', CHtml::listData(ChargeAdditionalCategory::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname'), array('class' => 'span4', 'empty' => 'root')); ?>
        <?php echo $form->dropDownListRow($model, 'account_id', array(0 => t('choose', 'global')) + CHtml::listData(Account::model()->findAll(), 'id', 'name'), array('class' => 'span4')); ?>
        <?php
        $transaction = SiteConfig::model()->getStandartTransactionMalang();
        $type_transaction = array();
        foreach ($transaction as $key => $value) {
            $type_transaction[$key] = '[ ' . $key . ' ] - ' . ucwords($value);
        }
        $data = array('' => '') + $type_transaction;

        echo $form->select2Row(
                $model, 'type_transaction', array(
            'asDropDownList' => true,
            'data' => $data,
            'options' => array(
                'placeholder' => 'Please Choose',
                'width' => '40%',
                "allowClear" => true,
            )
                )
        );
        ?>

        <?php // echo $form->textFieldRow($model, 'charge', array('class' => 'span5'));  ?>
        <?php
        echo $form->textFieldRow(
                $model, 'charge', array('prepend' => 'Rp',
            'class' => 'span3 changeTotal angka')
        );
        ?>
        <?php
        echo $form->textFieldRow(
                $model, 'discount', array('append' => '%',
            'class' => 'span1 changeTotal')
        );
        ?>

        <?php
        $totalCharge = $model->charge - (($model->discount / 100) * $model->charge);
        ?>

        <div class="control-group ">
            <label class="control-label" for="ChargeAdditional_charge">Total Charge</label>
            <div class="controls"><div class="input-prepend"><span class="add-on">Rp</span><input class="span3" value="<?php echo $totalCharge; ?>" name="totalCharge" id="totalCharge" disabled type="text"></div></div>
        </div>

        <?php
        echo $form->textAreaRow(
                $model, 'description', array('class' => 'span4', 'rows' => 5)
        );
        ?>

        <?php if (!isset($_GET['v'])) { ?>
            <div class="form-actions">
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'icon' => 'ok white',
                    'label' => $model->isNewRecord ? 'Create' : 'Save',
                ));
                ?>
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'reset',
                    'icon' => 'remove',
                    'label' => 'Reset',
                ));
                ?>
            </div>
        <?php } ?>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>

<script>
    $(".changeTotal").on("input", function () {
        var charge = $("#ChargeAdditional_charge").val();
        var discount = $("#ChargeAdditional_discount").val();
        charge = charge || 0;
        discount = discount || 0;
        var total = charge - ((discount / 100) * charge);
        $("#totalCharge").val(total);
    });
</script>
