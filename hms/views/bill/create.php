<?php
//$roomBillsGroup = RoomBill::model()->findAll(array('condition' => 'registration_id=' . 1, 'select' => '*, min(id) as min_id', 'order' => 'id', 'group' => 'room_number'));
//foreach ($roomBillsGroup as $a){
//    echo $a->id.'--'.$a->room_number.'<br>';
//}
?>
<?php
$this->setPageTitle('Guest Billing');
?>
<?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
//                array('label'=>'Edit', 'icon'=>'icon-edit', 'url'=>Yii::app()->controller->createUrl('update',array('id'=>$model->id)), 'linkOptions'=>array()),
    //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
//        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
)));
$this->endWidget();
?>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'sms-form',
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
    'enableClientValidation' => true,
    
        ));
?>


<hr>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<div class="control-group ">
    <label class="control-label" for="Reservation_guest_user_id">Check Out By :</label>
    <div class="controls">
        <?php echo CHtml::radioButtonList('checkoutBy', 1, array('1' => 'Room', '0' => 'Registration'), array('separator' => '')); ?>

        <?php
//        $this->widget(
//                'bootstrap.widgets.TbToggleButton', array(
//            'name' => 'checkoutBy',
//            'enabledLabel' => 'ROOM',
//            'value' => true,
//            'disabledLabel' => 'REGISTRATION',
//            'width' => '350',
//            'onChange' => 'js:function($el, status, e){console.log($el, status, e);}'
//                )
//        );
        ?>
    </div>
</div>
<div class="control-group byroom">
    <label class="control-label" for="Reservation_guest_user_id">Room Number : </label>
    <div class="controls">
        <?php
        $data = (!empty($room)) ? CHtml::listData($room, 'id', 'fullRoom') : array('0' => 'Please Choose');

        $this->widget(
                'bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => true,
            'name' => 'roomId',
            'value' => (!empty($number)) ? $number : '',
            'data' => $data,
            'options' => array(
                "placeholder" => 'Please Choose',
                "allowClear" => false,
                'width' => '70%',
            ),
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
                )
        );
        ?>
    </div>
</div>


<div class="control-group byregistration" style="display: none">
    <label class="control-label" for="Reservation_guest_user_id">Registration By :</label>
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
                                    $("#date_to").val("");
                                    $("#addRow").replaceWith(row);
                                    $("#pax").val("");
                                    $("#total").html(0);
                                    $("#grandTotal").val(0);
                                    $("#gl_room_bill_id").select2("val", 0);
                                    clearing();
                                }
                                $.ajax({
                                   url : "' . url('bill/getBilling') . '",
                                   type : "POST",
                                   data :  { regID:  $(this).val()},
                                   success : function(data){                                          
                                       obj = JSON.parse(data);                                       
                                       $(".items").remove();                                       
                                       $("#name").html(obj.name);
                                       $("#date_to").val(obj.date_to);
                                       $("#pax").val(obj.pax);
                                       $("#guest_phone").val(obj.guest_phone);
                                       $("#guest_address").val(obj.guest_address);
                                       $("#guest_company").val(obj.guest_company);
                                       $("#addRow").replaceWith(obj.detail);
                                       $("#total").html(obj.total);                                       
                                       $("#grandTotal").val(obj.grandTotal);                                       
                                       $("#totalDeposite").val(obj.totalDeposite); 
                                       $("#totalNoDeposite").val(obj.totalNoDeposite);
                                       $("#billedBy").select2("val",obj.ca);
                                       $("#refund").val(obj.grandTotal * -1);                                                                                                                    
                                       $("#cash").val(0);                                       
                                       $("#credit").val(0);                                       
                                       $("#cc_number").val("");                                       
                                       $("#cl").val(0);                                       
                                       $("#discount").val(0);                                       
                                   }
                                });
                       }'),
                )
        );
        ?>
    </div>
</div>

<hr>

<div class="box invoice">
    <div class="title clearfix">
        <h4 class="left">
            <span class="blue cut-icon-bookmark"></span>
            <span>Billing Detail</span>                        
        </h4>
        <div class="invoice-info">
            <span class="number"> Invoice #<strong class="red">
                    <?php
                    echo $model->code;
                    ?>
                </strong></span>
        </div> 
    </div>

    <div class="content">   


        <table style="width:100%">
            <tr>
                <td class="span2" style="vertical-align: top">Mr. / Mrs.</td>
                <td style="width:5px;vertical-align: top" colspan="3">: <input id="pax" type="text" class="span4 guest_reciver" name="guest_reciver" /></td>
            </tr>
            <tr>
                <td class="span2" style="vertical-align: top">Address</td>
                <td style="width:5px;vertical-align: top" colspan="3">: <input id="guest_address" type="text" class="span4 guest_address" name="guest_address" style="width:90%"/></td>
            </tr>
            <tr>
                <td class="span2" style="vertical-align: top">Company</td>
                <td style="width:5px;vertical-align: top">: <input id="guest_company" type="text" class="span2 guest_company" name="guest_company" /></td>
                <td class="span2" style="vertical-align: top">Phone</td>
                <td style="width:5px;vertical-align: top">: <input id="guest_phone" type="text" class="span2 guest_phone" name="guest_phone" /></td>
            </tr>
            <tr>
                <td style="vertical-align: top">Date Check In</td>
                <td style="vertical-align: top">: <input id="date_to" name="date_to" type="text" readonly value="<?php echo (!empty($dateTo)) ? $dateTo : ''; ?>"/></td>
                <td style="vertical-align: top">Date Check Out</td>                                
                <td style="vertical-align: top">: <?php echo date('l, d-M-Y H:i'); ?></td>
            </tr>                                                     
            <tr>
                <td style="vertical-align: top">Remarks</td>
                <td style="vertical-align: top" colspan="3">: <?php echo CHtml::textArea('description', '', array('style' => 'width:90%')); ?></td>
            </tr>                            


        </table>          

        <table class="table table-striped ">
            <thead>
                <tr>
                    <th style="width: 15px;text-align:center">#</th>
                    <th class="span4" style="text-align:center">Details</th>
                    <th class="span4" style="text-align:center">Room</th>
                    <th class="span3" style="text-align:center">Date</th>                                 
                    <th class="span1" style="text-align:center">Amount</th>                                 
                    <th class="span2" style="text-align:center">Charge</th>                                 
                    <th class="span2" style="text-align:center">Subtotal</th>                                 
                </tr>
            </thead>
            <tbody>     
                <?php
                if (!empty($_GET['number']))
                    echo $detail;
                ?>
                <tr id="addRow" style="display:none">
                    <td></td>
                    <td></td>                 
                    <td></td>                 
                </tr>
                <tr>
                    <td colspan="6" style="text-align: right">
                        <b>Grand Total :</b>  
                        <input type="hidden" name="grandTotal" id="grandTotal" value="<?php echo (!empty($total)) ? $total : 0; ?>" />
                        <input type="hidden" name="totalDeposite" id="totalDeposite" value="" />
                        <input type="hidden" name="totalNoDeposite" id="totalNoDeposite" value="" />
                    </td>
                    <td style="text-align:right"><span id="total"><?php echo landa()->rp((!empty($total)) ? $total : 0); ?></span></td>                                                        
                </tr>


                <tr>     
                    <td colspan="3" style="text-align: right">
                        <b>Guest Ledger :</b>                
                    </td>
                    <td colspan="2" style="text-align:right">
                        <?php
                        $roomBill = RoomBill::model()->findAll(array('condition' => 'is_checkedout=0 and lead_room_bill_id=0', "order" => "room_number Asc"));
                        $data = array(0 => 'Please Choose') + CHtml::listData($roomBill, 'id', 'fullRoom');
                        $this->widget(
                                'bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => true,
                            'name' => 'gl_room_bill_id',
                            'data' => $data,
                            'options' => array(
                                'allowClear' => true,
                                "placeholder" => 'Please Choose',
                                'width' => '85%',
                            ),
                                )
                        );
                        ?>                      
                    </td>  
                    <td style="text-align: right">
                        <b>Cash :</b>                
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input <?php echo (!empty($billTo)) ? 'disabled' : ''; ?> style="width:100px;" id="cash" name="cash"  type="text" class="angka">
                        </div>                        
                    </td>                                                        
                </tr>                               
                <tr >
                    <td colspan="3" style="text-align: right">
                        <b>Credit Card Number :</b>                        
                    </td>
                    <td colspan="2" style="text-align:right">
                        <input <?php echo (!empty($billTo)) ? 'disabled' : ''; ?> style="width:90%" id="cc_number" name="cc_number"  type="text">
                    </td>
                    <td style="text-align: right">
                        <b>Credit Card :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input <?php echo (!empty($billTo)) ? 'disabled' : ''; ?> style="width:100px;" id="credit" name="credit"  type="text" class="angka">
                        </div>
                    </td>                                                        
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right">
                        <b>City Ledger Name:</b>                        
                    </td>
                    <td colspan="2" style="text-align:right">
                        <?php
                        $datauser = CHtml::listData(User::model()->listUsers('guest'), 'id', 'fullName');
                        $this->widget(
                                'bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => true,
                            'value' => (!empty($billTo)) ? $billTo : '',
                            'name' => 'billedBy',
                            'data' => array(0 => 'Please Choose') + $datauser,
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => true,
                                "width" => '100%',
                        )));
                        ?>
                    </td>   
                    <td style="text-align: right">
                        <b>City Ledger :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input style="width:100px;" id="cl" name="cl"  type="text" class="angka">
                        </div>
                    </td>                                                        
                </tr> 
                <tr >
                    <td colspan="6" style="text-align: right">
                        <b>Discount :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend">
                            <span class="add-on">Rp</span>
                            <input class="angka" style="width:100px;" id="discount" name="discount"  type="text">

                        </div>
                    </td>                                                        
                </tr>
                <tr>                    
                    <td colspan="6" style="text-align: right">
                        <b>Refund</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input style="width:100px;" id="refund" name="refund" ReadOnly type="text" class="angka">                            
                        </div>
                    </td>                                                        
                </tr>

            </tbody>
        </table>  
    </div>
</div>    

<div class="form-actions">
    <button class="btn btn-primary" id="submit" type="submit" name="room"><i class="icon-ok icon-white"></i> Checkout</button>
</div>     
<?php
$this->endWidget();
$this->renderPartial('js');
?>
