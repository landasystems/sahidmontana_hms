<?php
$this->setPageTitle('Guest Billing');

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

<div class="control-group ">
    <label class="control-label" for="Reservation_guest_user_id">Find Room :</label>
    <div class="controls">
        <?php
        $data = CHtml::listData($room, 'id', 'fullRoom');
        $this->widget(
                'bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => true,
            'name' => 'roomId',
            'data' => array(0 => 'Please Choose') + $data,
            'options' => array(
                "placeholder" => 'Please Choose',
                "allowClear" => false,
                'width' => '50%',
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
                                       $("#date_to").html(obj.date_to);
                                       $("#addRow").replaceWith(obj.detail);
                                       $("#total").html(obj.total);                                       
                                       $("#grandTotal").val(obj.grandTotal);
                                       $("#idRoomBill").val(obj.idRoomBill);
                                       $("#ppn").html(obj.ppn);                                              
                                       var str = obj.code.split(",");                                       
                                       $("#billedBy").val(obj.billTo);                                                                             
                                       $("#s2id_billedBy .select2-choice").html(obj.billToName);
                                       if (obj.billTo==""){
                                            $("#cash").prop("disabled", false);
                                            $("#credit").prop("disabled", false);                                            
                                       }else{
                                            $("#cash").prop("disabled", true);
                                            $("#credit").prop("disabled", true);                                               
                                       }                                           
                                   }
                                });
                       }'),
                )
        );
        ?>
    </div>
</div>

<div id="taro" style="">
    <?php // echo $this->renderPartial('_linked', array('data' => $data,)); ?>
</div>

<hr>

<div class="box gradient invoice">
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
                <td class="span8" >
                    <table>
                        <tr>
                            <td class="span2">Room Number</td>
                            <td style="width:1px">:</td>
                            <td class="span10"><span id="number"></span></td>                                                     
                        </tr>
                        <tr>
                            <td>Mr. / Mrs.</td>
                            <td>:</td>
                            <td><span id="name"></span></td>                                
                        </tr>
                        <tr>
                            <td>Date Chack In</td>
                            <td>:</td>
                            <td><span id="date_to"></span></td>                                
                        </tr>                            

                    </table>
                </td>     
            </tr>
        </table>          

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width: 15px;text-align:center">#</th>
                    <th class="span4" style="text-align:center">Bill Name</th>
                    <th class="span1" style="text-align:center">R.Number</th>
                    <th class="span3" style="text-align:center">Date</th>                                 
                    <th class="span1" style="text-align:center">Amount</th>                                 
                    <th class="span2" style="text-align:center">Charge</th>                                 
                    <th class="span2" style="text-align:center">Subtotal</th>                                 
                </tr>
            </thead>
            <tbody>     

                <tr id="addRow" style="display:none">
                    <td></td>
                    <td></td>                 
                    <td></td>                 
                </tr>
                <tr>
                    <td colspan="6" style="text-align: right">
                        <b>Grand Total :</b>  
                        <input type="hidden" name="grandTotal" id="grandTotal" />
                    </td>
                    <td style="text-align:right"><span id="total"></span></td>                                                        
                </tr>
<!--                <tr>
                    <td colspan="6" style="text-align: right">
                        <b>PPN 10% :</b>                        
                    </td>
                    <td style="text-align:right" id="ppn"></td>                                                        
                </tr>-->
                <tr>
                    <td colspan="6" style="text-align: right">
                        <b>Billed By Other :</b>                        
                    </td>
                    <td style="text-align:right">
                        <?php
                        $datauser = CHtml::listData(Roles::model()->guest(), 'id', 'fullName');
                        $this->widget(
                                'bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => true,
                            'name' => 'billedBy',
                            'data' => array(0 => 'Please Choose') + $datauser,
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => false,
                            ),
                            'events' => array('change' => 'js: function() {
                              if($(this).val()>0){
                                $("#cash").prop("disabled", true);
                                $("#credit").prop("disabled", true);                                
                              }else{
                                $("#cash").prop("disabled", false);
                                $("#credit").prop("disabled", false);                                
                              }
                              
                                }'),)
                        );
                        ?>
                    </td>                                                        
                </tr>
                <tr>                    
                    <td colspan="6" style="text-align: right">
                        <b>By Cash :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input style="width:100px" id="cash" name="cash"  type="text">
                        </div>
                    </td>                                                        
                </tr>
                <tr>
                    <td colspan="6" style="text-align: right">
                        <b>By Credit Card :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input style="width:100px" id="credit" name="credit"  type="text">
                        </div>
                    </td>                                                        
                </tr>
                <tr>                    
                    <td colspan="6" style="text-align: right">
                        <b>Refund</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input style="width:100px" id="refundView" name="refundView" disabled="true" type="text">
                            <input id="refund" name="refund" type="hidden">
                        </div>
                    </td>                                                        
                </tr>

            </tbody>
        </table>  
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
