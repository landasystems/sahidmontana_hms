<?php
$this->setPageTitle('Extend');
$this->breadcrumbs = array(
    'Extend',
);


$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'sms-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<style>        
    .control-label{width: 100px !important}
    .controls {margin-left:120px !important}
    #cityq_guest{width:650px !important}
</style>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<div class="control-group ">
    <label class="control-label" for="Reservation_guest_user_id">Extend By :</label>
    <div class="controls">
        <?php
        $this->widget(
                'bootstrap.widgets.TbToggleButton', array(
            'name' => 'checkoutBy',
            'enabledLabel' => 'ROOM',
            'value' => true,
            'disabledLabel' => 'REGISTRATION',
            'width' => '250',
            'onChange' => 'js:function($el, status, e){console.log($el, status, e);selectBy();clearList();}'
                )
        );
        ?>
    </div>
</div>
<div class="control-group byroom">
    <label class="control-label" for="Reservation_guest_user_id">Room Number:</label>
    <div class="controls">
        <?php
        $data = (!empty($room)) ? CHtml::listData($room, 'id', 'fullRoom') : array('0' => 'Please Choose');

        $this->widget(
                'bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => true,
            'name' => 'roomId',
            'value' => $number,
            'data' => $data,
            'options' => array(
                "placeholder" => t('choose', 'global'),
                "allowClear" => false,
                'width' => '70%',
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
            'events' => array('change' => 'js: function() {
                                if ($(this).val() == null) {
                                    var row = "<tr id=\"addRow\" style=\"display:none\"></tr>";
                                    $(".items").remove();
                                    $("#addRow").html(row);
                                }
                                $.ajax({
                                   url : "' . url('roomBill/getRegister') . '",
                                   type : "POST",
                                   data :  { roomID:  $(this).val()},
                                   success : function(data){
                                        $(".items").remove();
                                        $("#addRow").html(data);
                                        calcExtend();
                                   }
                                });
                       }'),
                )
        );
        ?>
    </div>
</div>


<div class="control-group byregistration" style="display: none">
    <label class="control-label" for="Reservation_guest_user_id">Registration By:</label>
    <div class="controls">
        <?php
        $roomBill = RoomBill::model()->findAll(array('condition' => 'is_checkedout=0', 'group' => 'registration_id'));
        $dataq = CHtml::listData($roomBill, 'registration_id', 'registrationBy');
        $this->widget(
                'bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => true,
            'name' => 'registration_id',
            'data' => array(0 => t('choose', 'global')) + $dataq,
            'options' => array(
                "placeholder" => t('choose', 'global'),
                "allowClear" => false,
                'width' => '70%',
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
            'events' => array('change' => 'js: function() {
                                if ($(this).val() == null) {
                                    var row = "<tr id=\"addRow\" style=\"display:none\"></tr>";
                                    $(".items").remove();
                                    $("#addRow").html(row);
                                }
                                $.ajax({
                                   url : "' . url('roomBill/getRegister') . '",
                                   type : "POST",
                                   data :  { regID:  $(this).val()},
                                   success : function(data){
                                        $(".items").remove();
                                        $("#addRow").html(data);
                                        calcExtend();
                                   }
                                });
                       }'),
                )
        );
        ?>
    </div>
</div>
<div class="control-group ">
    <label class="control-label" for="until">Extend to :</label>
    <div class="controls">        
        <div class="input-prepend"><span class="add-on"><i class="icon-calendar"></i></span>
            <?php
            
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'extend',
                'value' => date('m/d/Y'),
                'options' => array(
                    'showAnim' => 'fold',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'minDate' => date('m/d/Y', strtotime(app()->session['date_system'] . ' +1 day')),
                ),
                'htmlOptions' => array(
                    'style' => 'height:20px;',
                    'id' => 'extend',
                    'onChange' => '
                        calcExtend();
                    '
                ),
            ));
            ?>
        </div>
        <span class="help-block">Guest Date Checkout</span>
        <input type="hidden"  id="current_check_out" name="current_check_out" value="<?php echo (!empty($checkOut)) ? $checkOut : ''; ?>" />
    </div>
</div>
<hr>


<div class="box gradient invoice">
    <div class="title clearfix">
        <h4 class="left">
            <span class="blue cut-icon-bookmark"></span>
            <span>Current Room Detail</span>                        
        </h4>
        <div class="invoice-info">
            <span class="number"> <strong class="red">
                </strong></span>
        </div> 
    </div>

    <div class="content">   


        <table style="width:100%">
            <tr>
                <td class="span8" >
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Registration Code</th>
                                <th>Room Number</th>
                                <th>Mr. / Mrs.</th>
                                <th>Date Check In</th>
                                <th>Date Check Out</th>
                                <th>Extends For</th>
                            </tr>
                        </thead>
                        <tbody id="addRow">
                            <?php
                            if (isset($_GET['roomNumber'])) {
                                $roombill = RoomBill::model()->find(array(
                                    'condition' => 'room_number=' . $_GET['roomNumber'] . ' AND lead_room_bill_id=0 AND is_checkedout=0',
                                    'order' => 'date_bill DESC',
                                        )
                                );
                                $checkout = RoomBill::model()->find(array(
                                    'condition' => 'room_id=' . $roombill->room_id,
                                    'order' => 'date_bill DESC',
                                    'limit' => '1'
                                ));
                                echo '<tr class="items">
                                    <td><i class="icomoon-icon-arrow-right-7"></i>
                                        <input type="hidden" id="regId" name="id[]" value="' . $roombill->registration_id . '" />
                                        <input type="hidden" id="roomNumber" name="roomId[]" value="' . $roombill->room_number . '" />
                                        <input type="hidden" id="dateIn" name="dateIn[]" value="' . $roombill->Registration->date_from . '" />
                                        <input type="hidden" id="dateOut" class="dateOut" name="dateOut[]" value="' . date('m/d/Y', strtotime($checkout->date_bill) + 86400) . '" />
                                        </td>
                                        <td>' . $roombill->registration_id . '</td>
                                        <td>' . $roombill->room_number . '</td>
                                        <td>' . $roombill->Registration->Guest->name . '</td>
                                        <td>' . date('d F Y', strtotime($roombill->Registration->date_from)) . '</td>
                                        <td>' . date('d F Y', strtotime($checkout->date_bill) + 86400) . '</td>
                                        <td class="ext"></td>
                                        </tr>';
                            }
                            ?>

                        </tbody>
                    </table>
                </td>     
            </tr>
        </table>          
    </div>
</div>    

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => 'Extend Room',
    ));
    ?>
</div>
</fieldset>

<?php $this->endWidget(); ?>

<script>
    function selectBy() {
        if ($('#checkoutBy').attr('checked')) {
            $(".byroom").show();
            $("#submit").attr("name", "room");
            $(".byregistration").hide();
        } else {
            $(".byroom").hide();
            $("#submit").attr("name", "registration");
            $(".byregistration").show();

        }
    }
    function clearList() {
        $(".items").remove();
//        var row = "<tr id=\"addRow\" style=\"display:none\"></tr>";
//        $("#addRow").replaceWith(row);
    }
    function calcExtend() {
        var akhir = $(".hasDatepicker").val();
        $(".dateOut").each(function () {
            var awal = $(this).val();
            var dateAkhir = new Date(akhir);
            var dateAwal = new Date(awal);
            var diff = (dateAkhir - dateAwal) / (60 * 60 * 24 * 1000);
            
            if (diff <= 0)
                diff = '-';
            
            $(this).parent().parent().find(".ext").html(diff + ' day');
        })

    }
//    $("#addRow").on("load", function() {
//        $.ajax({
//            url: "' . url('roomBill/getRegister') . '",
//            type: "POST",
//            data: {regID: $(this).val()},
//            success: function(data) {
//                $(".items").remove();
//                $("#addRow").replaceWith(data);
//                calcExtend();
//            }
//        });
//    });
    $("#roomId").trigger("change");

</script>