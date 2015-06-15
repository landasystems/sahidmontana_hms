<?php
if (!empty($_GET['number'])) {
    $id = $_GET['number'];
    $model = Room::model()->findByPk($id);
    $roomNumber = $model->number;
    $guestName = $model->Registration->Guest->name;
    $dateCheckIn = date("l Y-m-d h:i:s", strtotime($model->Registration->created));
    $roomBills = RoomBill::model()->findAll(array('condition' => 'room_id=' . $id . ' and is_checkedout=0 and processed=1'));
    $total = 0;
}
?>

<?php
$this->setPageTitle('Charge Guest Ledger');
$this->breadcrumbs = array(
    'Charge Guest Ledger',
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
    .control-label{width: 90px !important}
    .controls {margin-left:110px !important}
    #cityq_guest{width:650px !important}
</style>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert fade in alert-' . $key . '">' . $message . '</div>';
}
?>
<div class="control-group ">
    <label class="control-label" for="Reservation_guest_user_id">Find Room :</label>
    <div class="controls">
        <?php
        $number = (!empty($_GET['number'])) ? $_GET['number'] : '';

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
                'width' => '100%',
            ),
            'events' => array('change' => 'js: function() {
                                $.ajax({
                                   url : "' . url('roomBill/getBilling') . '",
                                   type : "POST",
                                   data :  { regID:  $(this).val()},
                                   success : function(data){                                        
                                       obj = JSON.parse(data);
                                       $(".items").remove();
                                       $("#number").html(obj.number);
                                       $("#roomNumber").val(obj.number);
                                       $("#name").html(obj.name);
                                       $("#guestSign").html(obj.name);
                                       $("#date_to").html(obj.date_to);
                                       //$("#addRow").replaceWith(obj.detail);
                                       //$("#total").html(obj.total);                                       
                                       //$("#grandTotal").val(obj.grandTotal);
                                       $("#idRoomBill").val(obj.idRoomBill);
                                       
                                   }
                                });
                       }'),
                )
        );
        ?>
    </div>
</div>

<hr>


<div class="box gradient invoice">
    <div class="title clearfix">
        <h4 class="left">
            <span class="blue cut-icon-bookmark"></span>
            <span>Charge Guest Ledger</span>                        
        </h4>
        <div class="invoice-info">
            <span class="number"> <strong class="red">
                    <?php
//                        echo $model->code;
                    ?>
                </strong></span>


        </div> 
    </div>

    <div class="content">   


        <table style="width:100%">
            <tr>
                <td style="width:150px">Room Number</td>
                <td  style="width:5px">:</td>
                <td><span id="number"><?php echo (!empty($roomNumber)) ? $roomNumber : '' ?></span></td>                                                     
            </tr>
            <tr>
                <td >Mr. / Mrs.</td>
                <td  style="width:5px">:</td>
                <td ><span id="name"><?php echo (!empty($guestName)) ? $guestName : '' ?></span></td>                                
            </tr>
            <tr>
                <td >Date </td>
                <td  style="width:5px">:</td>
                <td ><span id="date_to"><?php echo (!empty($dateCheckIn)) ? $dateCheckIn : '' ?></span></td>                                
            </tr>                            


        </table>          

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width: 15px;text-align:center">#</th>
                    <th class="span4" style="text-align:center">Bill Name</th>
                    <th class="span1" style="text-align:center">R.Number</th>
                    <!--<th class="span3" style="text-align:center">Date</th>-->                                 
                    <th class="span1" style="text-align:center">Amount</th>                                 
                    <th class="span2" style="text-align:center">Charge</th>                                 
                    <th class="span2" style="text-align:center">Subtotal</th>                                 
                </tr>
            </thead>
            <tbody>     
                <tr class="hidePrint">
                    <td style="text-align:center">
                        <?php
                        echo CHtml::ajaxLink(
                                $text = '<i class="icon-plus-sign"></i>', $url = url('roomBill/addRow'), $ajaxOptions = array(
                            'type' => 'POST',
                            'success' => 'function(data){                                       
                                                $("#addRow").replaceWith(data); 
                                                subtotal(0);
                                                clearField();
                                                $(".delRow").on("click", function() {
                                                    $(this).parent().parent().remove();
                                                    subtotal(0);
                                                });
                                            
                                        }'), $htmlOptions = array()
                        );
                        ?>                        
                    </td>
                    <td>                        
                        <?php
                        $data2 = RoomBill::model()->getAdditional();
                        $this->widget(
                                'bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => true,
                            'name' => 'additional_id',
                            'data' => $data2,
                            'options' => array(
                                "placeholder" => t('choose', 'global'),
                                "allowClear" => false,
                                'width' => '100%',
                            ),
                            'events' => array('change' => 'js: function() {
                                $.ajax({
                                   url : "' . url('roomBill/getAdditional') . '",
                                   type : "POST",
                                   data :  { addID:$(this).val(),roomNumber:$("#roomNumber").val()},
                                   success : function(data){                                                                               
                                       if (data==""){
                                        $("#addDate").html("");
                                        $("#addPrice").html("");                                            
                                       }                          
                                       clearField();
                                       obj = JSON.parse(data);                                       
                                       $("#addDate").html(obj.date);
                                       $("#addPrice").html(obj.charge);  
                                       $("#addRoomNumber").html(obj.number);                                       
                                      
                                   }
                                });
                       }'),
                                )
                        );
                        ?>                            
                    </td>
                    <td style="text-align:center" id="addRoomNumber"></td>                                                        
                    <!--<td id="addDate"></td>-->                                                        
                    <td style="text-align: center"><input type="text" maxlength="6" name="amount" id="amount" class="span1"/></td>                                                        
                    <td style="text-align: right" id="addPrice"></td>                                                        
                    <td style="text-align: right" id="addSubtotal"></td>                                                        
                </tr>
                <?php
                if (!empty($_GET['number'])) {
                    foreach ($roomBills as $roomBill) {
                        echo '<tr class="items">
                        <td style="text-align:center"><a href="#" id="addItem"><i class="brocco-icon-forward"></i></a>                                                    
                        </td>
                        <td>Room Charge</td>
                        <td style="text-align:center">' . $model->number . '</td>
                        <td>' . date("l Y-m-d H:i:s", strtotime($roomBill->date_bill)) . '</td>                                                        
                        <td style="text-align:center">1</td>                        
                        <td style="text-align:right">' . landa()->rp($roomBill->charge) . '</td>                                                        
                        <td style="text-align:right">' . landa()->rp($roomBill->charge) . '</td>                                                        
                    </tr>';
                        $total+= $roomBill->charge;
                        $additionBills = RoomBillDet::model()->findAll(array('condition' => 'room_bill_id=' . $roomBill->id));
                        foreach ($additionBills as $additionBill) {
                            echo '<tr class="items">
                        <td style="text-align:center">
                            <a href="#" id="addItem"><i class="brocco-icon-forward"></i></a>                            
                        </td>
                        <td> &nbsp;&nbsp;&raquo; ' . $additionBill->Additional->name . '</td>
                        <td style="text-align:center">' . $model->number . '</td>
                        <td> &nbsp;&nbsp;&raquo; ' . date("H:i:s", strtotime($additionBill->created)) . '</td>                                                        
                        <td style="text-align:center">' . $additionBill->amount . '</td>                                                        
                        <td style="text-align:right">' . landa()->rp($additionBill->charge) . '</td>                                                        
                        <td style="text-align:right">' . landa()->rp($additionBill->charge) . '</td>                                                        
                    </tr>';
                            $total+= $additionBill->charge;
                        }
                    }
                    $roomBillId = $roomBill->id;
                }
                ?>


                <tr id="addRow" style="display:none">
                    <td></td>
                    <td></td>                 
                    <td></td>                 
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right">
                        <b>Grand Total :</b>    
                        <input type="hidden" name="idRoomBill" id="idRoomBill" value="<?php echo (!empty($roomBillId)) ? $roomBillId : ''; ?>" />
                        <input type="hidden" name="roomNumber" id="roomNumber" value="<?php echo (!empty($number)) ? $number : ''; ?>" />
                        <input type="hidden" id="grandTotal" name="total" value="<?php echo (!empty($total)) ? $total : 0; ?>" />
                    </td>
                    <td style="text-align:right"><span id="total"><?php echo (!empty($total)) ? landa()->rp($total) : '' ?></span></td>                                                        
                </tr>

            </tbody>
        </table>          
        <table style="width:100%">
            <tr>
                <td style="width:50%;text-align: center;vertical-align: top">
                    <br>
                    Guest Sign
                    <br>
                    <br>
                    <br>
            <u>......................................</u>
            </td>
            <td style="width:50%;text-align: center;vertical-align: top">
                <br>
                Cashier
                <br>
                <br>
                <br>
            <u><?php echo Yii::app()->user->name; ?></u>
            </td>
            </tr>
        </table>
    </div>
</div>    

<div class="form-actions">
    <button class="btn btn-primary"  type="submit" onclick="printDiv()"><i class="icon-ok icon-white"></i> Print & Save</button>
    <?php
//    $this->widget('bootstrap.widgets.TbButton', array(
//        'buttonType' => 'submit',
//        'type' => 'primary',
//        'icon' => 'ok white',
//        'label' => $model->isNewRecord ? 'Create' : 'Save',
//    ));
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

<style type="text/css" media="print">
    body {visibility:hidden;}
    .invoice{visibility:visible;}  
    .hidePrint{display: none;}  
    /*.tes{width: 200px;}*/  
    #sidebar{display: none;}  
    #content{margin: 10px;padding: 10px;position: relative;top: -150px}  

</style>
<script type="text/javascript">
    function printDiv()
    {                    
        window.print();
    }
</script>