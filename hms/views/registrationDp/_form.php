<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'room-bill-dp-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    
    <?php
    if ($model->isNewRecord == false){
        $name = $model->Registration->Guest->name;
        
    }
    ?>
    <fieldset>
        <legend>
            <p class="note">Fields with <span class="required">*</span> is Required.</p>
        </legend>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>

        <div class="control-group ">
            <label class="control-label" for="Reservation_guest_user_id">Find User </label>
            <div class="controls">
                <?php
//                $number = (!empty($_GET['number'])) ? $_GET['number'] : '';
                $room = Room::model()->findAll(array('group' => 'registration_id', 'condition' => 'status="occupied"'));
                $data = array(0 => 'Please Choose') + CHtml::listData($room, 'registration_id', 'guestRegistered');

                $this->widget(
                        'bootstrap.widgets.TbSelect2', array(
                    'asDropDownList' => true,
                    'name' => 'RegistrationDp[registration_id]',
                    'value' => $model->registration_id,
                    'data' => $data,
                    'options' => array(
                        "placeholder" => 'Please Choose',
                        "allowClear" => true,
                        'width' => '30%',
                    ),
                    'events' => array('change' => 'js: function() {
                                $.ajax({
                                   url : "' . url('RegistrationDp/getRegistration') . '",
                                   type : "POST",
                                   data :  { registration_id:  $(this).val()},
                                   success : function(data){                                         
                                       obj = JSON.parse(data);                                                                                                                    
                                       $("#name").val(obj.name);                                                                  
                                   }
                                });
                       }'),
                        )
                );
                ?>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="Room_number">Mr. / Mrs.</label>
            <div class="controls"><input class="span3" maxlength="30" name="name" value="<?php echo (!empty($name))?$name:'';?>" id="name" disabled="true" type="text">
            </div>
        </div>
        <?php echo $form->dropdownListRow($model, 'by', array('cash' => 'By Cash', 'cc' => 'By Credit Card', 'debit' => 'By Debit Card')); ?>

        <?php
        echo $form->textFieldRow($model, 'amount', array('class' => 'input-medium',
            'prepend' => 'Rp'));
        ?>        
        
            <?php echo $form->textFieldRow($model, 'cc_number', array('class' => 'span3', 'maxlength' => 45));
            ?>
        

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
    </fieldset>
    <?php $this->endWidget(); ?>

</div>

<script>
    function type() {
        if ($("#type").is(":checked")) {
            $("#RoomBillDp_cc_number").val("");
            $(".card").hide();
        } else {
            $(".card").show();
        }
    }
</script>