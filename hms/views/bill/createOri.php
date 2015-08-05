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
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
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
    <label class="control-label" for="Reservation_guest_user_id">Room Number :</label>
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
                                    $("#name").html("");
                                    $("#date_to").html("");
                                    $("#addRow").replaceWith(row);
                                    $("#pax").val("");
                                    $("#total").html(0);
                                    $("#grandTotal").val(0);
                                    $("#totalDeposite").val(0);
                                    $("#totalNoDeposite").val(0);
                                    $("#cash").val(0);
                                    $("#credit").val(0);
                                    $("#cc_number").val("");
                                    $("#cl").val(0);
                                    $("#billedBy").select2("val", 0);
                                    $("#refund").val(0);
                                    $("#discount").val(0);
                                }
                                $.ajax({
                                   url : "' . url('bill/getBilling2') . '",
                                   type : "POST",
                                   data :  { regID:  $(this).val()},
                                   success : function(data){                                          
                                       obj = JSON.parse(data);                                       
                                       $(".items").remove();                                       
                                       $("#name").html(obj.name);
                                       $("#date_to").html(obj.date_to);
                                       $("#pax").val(obj.name);
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
<!--            <tr>
                <td class="span2" style="vertical-align: top">Registered By</td>
                <td style="width:5px;vertical-align: top">:</td>
                <td style="vertical-align: top"><span id="name"></span></td>                                
            </tr>-->
            <tr>
                <td class="span2" style="vertical-align: top">Mr. / Mrs.</td>
                <td style="width:5px;vertical-align: top">:</td>
                <td style="vertical-align: top">
                    <!--<span id="pax"></span>-->
                    <input id="pax" type="text" class="span4 guest_reciver" name="guest_reciver" />
                </td>                                
            </tr>
            <tr>
                <td style="vertical-align: top">Date Check In</td>
                <td>:</td>
                <td style="vertical-align: top"><span id="date_to"><?php echo (!empty($dateTo)) ? $dateTo : ''; ?></span></td>                                
            </tr>                            
            <tr>
                <td style="vertical-align: top">Date Check Out</td>
                <td>:</td>
                <td style="vertical-align: top"><?php echo date('l Y-m-d H:i:s'); ?></td>                                
            </tr>                            
            <tr>
                <td style="vertical-align: top">Remarks</td>
                <td style="vertical-align: top">:</td>
                <td style="vertical-align: top">
                    <?php echo CHtml::textArea('description', '', array('style' => 'width:100%')); ?>
                </td>                                
            </tr>                            


        </table>          

        <table class="table table-striped ">
            <thead>
                <tr>
                    <th style="width: 15px;text-align:center">#</th>
                    <th class="span4" style="text-align:center">Bill Name</th>
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
                    <td colspan="5" style="text-align: right">
                        <b>Grand Total :</b>  
                        <input type="hidden" name="grandTotal" id="grandTotal" value="<?php echo (!empty($total)) ? $total : 0; ?>" />
                        <input type="hidden" name="totalDeposite" id="totalDeposite" value="" />
                        <input type="hidden" name="totalNoDeposite" id="totalNoDeposite" value="" />
                    </td>
                    <td style="text-align:right"><span id="total"><?php echo landa()->rp((!empty($total)) ? $total : 0); ?></span></td>                                                        
                </tr>



                <tr>                        
                    <td colspan="5" style="text-align: right">
                        <b>Cash :</b>                
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input <?php echo (!empty($billTo)) ? 'disabled' : ''; ?> style="width:100px;direction: rtl" id="cash" name="cash"  type="text" class="angka">
                        </div>                        
                    </td>                                                        
                </tr>                
                <tr >
                    <td colspan="2" style="text-align: right">
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
                            <input <?php echo (!empty($billTo)) ? 'disabled' : ''; ?> style="width:100px;direction: rtl" id="credit" name="credit"  type="text" class="angka">
                        </div>
                    </td>                                                        
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right">
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
                                "allowClear" => false,
                                "width" => '100%',
                        )));
                        ?>
                    </td>   
                    <td style="text-align: right">
                        <b>City Ledger :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input style="width:100px;direction: rtl" id="cl" name="cl"  type="text" class="angka">
                        </div>
                    </td>                                                        
                </tr> 
                <tr >
                    <td colspan="5" style="text-align: right">
                        <b>Discount :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend">
                            <span class="add-on">Rp</span>
                            <input class="angka" style="width:100px;direction: rtl" id="discount" name="discount"  type="text">

                        </div>
                    </td>                                                        
                </tr>
                <tr>                    
                    <td colspan="5" style="text-align: right">
                        <b>Refund</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input style="width:100px;direction: rtl" id="refund" name="refund" ReadOnly type="text" class="angka">                            
                        </div>
                    </td>                                                        
                </tr>

            </tbody>
        </table>  
    </div>
</div>    

<div class="form-actions">
    <button class="btn btn-primary" id="submit" type="submit" name="room"><i class="icon-ok icon-white"></i> Create</button>
</div>
</div>      
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
    $("#roomId").on("change", function() {
        if ($(this).val() == null) {
            var row = "<tr id=\"addRow\" style=\"display:none\"></tr>";
            $(".items").remove();
            $("#name").html("");
            $("#date_to").html("");
            $("#addRow").replaceWith(row);
            $("#pax").val("");
            $("#total").html(0);
            $("#grandTotal").val(0);
            $("#totalDeposite").val(0);
            $("#totalNoDeposite").val(0);
            $("#cash").val(0);
            $("#credit").val(0);
            $("#cc_number").val("");
            $("#cl").val(0);
            $("#billedBy").select2("val", 0);
            $("#refund").val(0);
            $("#discount").val(0);
        }
        $.ajax({
            url: "<?php echo url('bill/getBilling'); ?>",
            type: "POST",
            data: {regID: $(this).val()},
            success: function(data) {
                obj = JSON.parse(data);
                $(".items").remove();
                $("#name").html(obj.name);
                $("#date_to").html(obj.date_to);
                $("#addRow").replaceWith(obj.detail);
                $("#pax").val(obj.pax);
                $("#total").html(obj.total);
                $("#grandTotal").val(obj.grandTotal);
                $("#totalDeposite").val(obj.totalDeposite);
                $("#totalNoDeposite").val(obj.totalNoDeposite);
                $("#cash").val(0);
                $("#credit").val(0);
                $("#cc_number").val("");
                $("#cl").val(0);
                $("#billedBy").select2("val", obj.ca);
                $("#refund").val(obj.grandTotal * -1);
                $("#discount").val(0);
            }
        });
    });

    $("#roomId").trigger("change");

</script>