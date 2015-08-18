<?php
$this->setPageTitle('Extend');

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
    <label class="control-label">Extend By</label>
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
    <label class="control-label required">Room Number</label>
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
                "placeholder" => 'Please Choose',
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
            'data' => array(0 => 'Please Choose') + $dataq,
            'options' => array(
                "placeholder" => 'Please Choose',
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
    <label class="control-label required">Extend to</label>
    <div class="controls">        
        <div class="input-prepend"><span class="add-on"><i class="icon-calendar"></i></span>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'extend',
                'value' => date('d M Y', strtotime(app()->session['date_system']. ' +1 day')),
                'options' => array(
                    'dateFormat'=>'d M yy',
                    'showAnim' => 'fold',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'minDate' => date('d M Y', strtotime(app()->session['date_system'] . ' +1 day')),
                ),
                'htmlOptions' => array(
                    'style' => 'height:20px;width:100px',
                    'id' => 'extend',
                    'onChange' => '
                        calcExtend();
                    '
                ),
            ));
            ?>
        </div>
        <span class="help-block">Guest Departure Date</span>
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

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th>Registration Code</th>
                <th>Room Number</th>
                <th>Mr. / Mrs.</th>
                <th>Arrival</th>
                <th>Departure</th>
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

</div>    

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => 'Extend',
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

    $("#roomId").trigger("change");

</script>