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
    <fieldset>
        <legend>
            <p class="note">Fields with <span class="required">*</span> is Required.</p>
        </legend>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>

        <div class="control-group ">
            <label class="control-label" for="Reservation_guest_user_id">Find Room </label>
            <div class="controls">
                <?php
                $number = (!empty($_GET['number'])) ? $_GET['number'] : '';
                $room = Room::model()->findAll(array('condition' => 'status="occupied"'));
                $data = array(0 => t('choose', 'global')) + CHtml::listData($room, 'id', 'fullRoom');
                $this->widget(
                        'bootstrap.widgets.TbSelect2', array(
                    'asDropDownList' => true,
                    'name' => 'roomId',
                    'value' => $number,
                    'data' => $data,
                    'options' => array(
                        "placeholder" => t('choose', 'global'),
                        "allowClear" => true,
                        'width' => '30%',
                    ),
                    'events' => array('change' => 'js: function() {
                                $.ajax({
                                   url : "' . url('roomBillDp/getRoomBill') . '",
                                   type : "POST",
                                   data :  { roomId:  $(this).val()},
                                   success : function(data){                                         
                                       obj = JSON.parse(data);                                       
                                       $("#room_bill_id").val(obj.room_bill_id);                                       
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
            <div class="controls"><input class="span3" maxlength="30" name="name" id="name" disabled="true" type="text">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="type">Type</label>
            <div class="controls">
                <?php
                $this->widget(
                        'bootstrap.widgets.TbToggleButton', array(
                    'name' => 'type',
                    'enabledLabel' => 'By Cash',
                    'disabledLabel' => 'By Credit Card',
                    'width' => 230,
                    'value' => true,
                    'onChange' => 'js:function($el, status, e){console.log($el, status, e);type();}'
                        )
                );
                ?>
            </div>
        </div>

        <?php
        echo $form->textFieldRow($model, 'amount', array('class' => 'input-medium',
            'prepend' => 'Rp'));
        ?>
        <input type="hidden" name="RoomBillDp[room_bill_id]" value="" id="room_bill_id"/>
        <div class="card" style="display: none">
            <?php echo $form->textFieldRow($model, 'cc_number', 
                    array('class' => 'span3', 'maxlength' => 45,'hint' => 'Fill this field if type is Credit Card, But if not type will assumed as Cash')); ?>
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