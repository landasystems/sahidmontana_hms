<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<table class="table table-striped table-bordered">
    <thead>

        <tr>
            <th style="width: 15px;text-align:center">#</th>
            <th class="span5" style="text-align:center">Item Name</th>                             
            <th class="span1" style="text-align:center">Amount</th>                                 
            <th class="span2" style="text-align:center">Charge</th>                                 
            <th class="span2" style="text-align:center">Discount</th>                                 
            <th class="span2" style="text-align:center">Subtotal</th>                                 
        </tr>
    </thead>
    <tbody>     
        <tr class="hidePrint">
            <td style="text-align:center;vertical-align: middle;background: lightgrey">
                <?php
                echo CHtml::ajaxLink(
                        $text = '<button><i class="icon-plus-sign"></i></button>', $url = url('billCharge/addRow'), $ajaxOptions = array(
                    'type' => 'POST',
                    'success' => 'function(data){
                                                $("#addRow").replaceWith(data); 
                                                subtotal(0);
                                                clearField();
                                                $(".delRow").on("click", function() {
                                                    $(this).parent().parent().remove();
                                                    subtotal(0);
                                                });
                                                $("#s2id_additional_id").select2("data", null)
                                        }')
                );
                ?>                        
            </td>
            <td style="text-align:center;vertical-align: middle;background: lightgrey">                        
                <?php
//                if (!empty($id)) {
//                    $data2 = RoomBill::model()->getAdditional($id);
////                    $data2 = array('0' => 'Please Choose') + CHtml::listData(ChargeAdditional::model()->findAll(), 'id', 'fullInitialCategory');
//                } else {
//                    $data2 = array('0' => 'Please Choose');
//                }
//                $data2 = array('0' => 'Please Choose') + CHtml::listData(ChargeAdditional::model()->findAll(array('condition' => 'is_publish=1')), 'id', 'fullInitialCategory');
                $this->widget(
                        'bootstrap.widgets.TbSelect2', array(
//                    'asDropDownList' => true,                    
                    'name' => 'additional_id',
                    'asDropDownList' => false,
//                    'data' => $data2,
                    'options' => array(
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'width' => '100%;margin:0px;text-align:left',
                        'minimumInputLength' => '3',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('chargeAdditional/getChargeAdditional'),
                            'dataType' => 'json',
                            'data' => 'js:function(term, page) { 
                                                        return {
                                                            q: term 
                                                        }; 
                                                    }',
                            'results' => 'js:function(data) { 
                                                        return {
                                                            results: data
                                                        };
                                                    }',
                        ),
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
                                       $("#charge").val(obj.charge);  
                                       $("#discount").val(obj.discount);  
                                       $("#addRoomNumber").html(obj.number);                                       
                                      
                                   }
                                });
                       }'),
                        )
                );
                ?>     
            </td>                                                                                
            <td style="text-align:center;vertical-align: middle;background: lightgrey"><input type="text" maxlength="6" name="amount" id="amount" class="span1"/></td>
            <td style="text-align: right;vertical-align: middle;background: lightgrey" id="">
                <div class="input-prepend">
                    <span class="add-on">Rp</span>
                    <input readonly="true" type="text"  value="0" name="charge" id="charge" class="changeDiscount angka">                    
                </div>                
            </td>                                                                    
            <td style="text-align: center;vertical-align: middle;background: lightgrey">
                <div class="input-append">
                    <input style="width: 30px" readonly="true" type="text" maxlength="3" value="0" name="discount" id="discount" class="changeDiscount">
                    <span class="add-on">%</span>
                </div>
            </td>
            <td style="text-align: right;vertical-align: middle;background: lightgrey" id="addSubtotal"></td>                                                        
        </tr>                      

        <?php
        if ($model->isNewRecord == FALSE) {
            $details = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $model->id));
            foreach ($details as $detail) {
                if ($detail->deposite_id != 0) {
                    echo '<tr class="items">
                        <input type="hidden" name="deposite[id][]" id="deposite' . $detail->Deposite->id . '" value="' . $detail->Deposite->id . '"/>                        
                        <td style="text-align:center"><button class="delRow "><i class="icon-remove-circle" style="cursor:all-scroll;"></i></button></td>
                        <td>[' . $detail->Deposite->code . '] ' . $detail->Deposite->Guest->guestName . '</td>
                        <td style="text-align:center"><input type="text" readOnly maxlength="6" class="span1" name="" id="" value=""/></td>
                        <td style="text-align:right">
                            <div class="input-prepend">
                                <span class="add-on">Rp</span>                                
                                <input class="depositeAmount changeDiscount" id="changeDiscount depositeAmount" readOnly name="deposite[amount][]" type="text"  value="' . $detail->deposite_amount . '" >                                
                            </div>
                        </td>                                                        
                        <td style="text-align:center">
                            <div class="input-append">
                                <input style="width: 30px" class="" readOnly type="text" maxlength="3" value="" name="" id="" >
                                <span class="add-on">%</span>
                            </div>
                        </td>                                                        
                        <td style="text-align:right" class="subtot">' . landa()->rp($detail->deposite_amount) . '</td>                                                        
                    </tr>
                     <tr id="addDeposite" style="display:none">
                    </tr>  ';
                } else {
                    $total = ($detail->charge * $detail->amount) - round(($detail->discount / 100) * ($detail->charge * $detail->amount));
                    echo '                                                  
                    <tr class="items">
                        <input type="hidden" name="detail[id][]" id="' . $detail->charge_additional_id . '" value="' . $detail->charge_additional_id . '"/>                        
                        
                        <input type="hidden" name="detail[total][]" id="detTotal" class="detTotal" value="' . $total . '"/>                                                                                                                                                         
                        <td style="text-align:center"><button class="delRow"><i class="icon-remove-circle" style="cursor:all-scroll;"></i></button></td>
                        <td> &nbsp;&nbsp;&raquo; ' . $detail->Additional->fullInitialCategory . '</td>
                        <td style="text-align:center"><input type="text" maxlength="6" class="span1 detQty" name="detail[amount][]" id="detQty" value="' . $detail->amount . '"/></td>' .
                    '<td style="text-align:right">
                            <div class="input-prepend">
                                <span class="add-on">Rp</span>
                                <input class="detCharge changeDiscount angka" readOnly type="text" value="' . $detail->charge . '" name="detail[charge][]" id="detCharge" >                                
                            </div>  
                        </td>                                                        
                        <td style="text-align:center">
                            <div class="input-append">
                                <input style="width: 30px" readOnly class="detDiscount changeDiscount" type="text" maxlength="3" value="' . $detail->discount . '" name="detail[discount][]" id="detDiscount" >
                                <span class="add-on">%</span>
                            </div>                        
                        </td>                                                        
                        <td style="text-align:right">' . landa()->rp($total) . '</td>
                    </tr>
                    
                     <tr id="addRow" style="display:none">
                    </tr>                       
                    ';
                }
            }
        }
        ?>
        <tr id="addDeposite" style="display:none"></tr>
        <tr id="addRow" style="display:none"></tr>
        <tr>
            <td colspan="5" style="text-align: right">
                <b>Grand Total :</b>                    
                <input type="hidden" id ="BillCharge_total" name="BillCharge[total]" value="<?php echo $model->total ?>" />
            </td>
            <td style="text-align:right"><span id="total"><?php echo (!empty($model->total)) ? landa()->rp($model->total) : '' ?></span></td>                                                        
        </tr>                                
        <tr class="cash">
            <td colspan="5" style="text-align: right">
                <b>Cash :</b>                                    
            </td>

            <td style="text-align:right">
                <div class="input-prepend"><span class="add-on">Rp</span>
                    <?php echo Chtml::textField('BillCharge[cash]', $model->cash, array('class' => 'span2 angka')); ?>
                </div>
            </td>
        </tr>
        <tr class="cc debit">
            <td colspan="2" style="text-align: right">
                <b>Credit Card Number :</b>                                    
            </td>
            <td colspan="2" style="text-align: right">
                <?php echo Chtml::textField('BillCharge[cc_number]', $model->cc_number, array('class' => 'span3')); ?>                        
            </td>
            <td  style="text-align: right">
                <b>Credit Card :</b>                                    
            </td>
            <td style="text-align:right">
                <div class="input-prepend"><span class="add-on">Rp</span>
                    <?php echo Chtml::textField('BillCharge[cc_charge]', $model->cc_charge, array('class' => 'span2 angka')); ?>
                </div>
            </td>
        </tr>
        <tr class="cc debit">
            <td colspan="2" style="text-align: right">
                <b>Guest Ledger Name :</b>                                    
            </td>
            <td colspan="2" style="text-align: right">
                <?php
                $roomBill = RoomBill::model()->findAll(array('condition' => 'is_checkedout=0 and lead_room_bill_id=0', "order" => "room_number Asc"));
                $data = array(0 => t('choose', 'global')) + CHtml::listData($roomBill, 'id', 'fullRoom');
                $this->widget(
                        'bootstrap.widgets.TbSelect2', array(
                    'asDropDownList' => true,
                    'name' => 'BillCharge[gl_room_bill_id]',
                    'value' => $model->gl_room_bill_id,
                    'data' => $data,
                    'options' => array(
                        'allowClear' => true,
                        "placeholder" => t('choose', 'global'),
                        'width' => '85%',
                    ),
                        )
                );
                ?>                         
            </td>
            <td  style="text-align: right">
                <b>Guest Ledger :</b>                                    
            </td>
            <td style="text-align:right">
                <div class="input-prepend"><span class="add-on">Rp</span>
                    <?php echo Chtml::textField('BillCharge[gl_charge]', $model->gl_charge, array('class' => 'span2 angka')); ?>
                </div>  
            </td>
        </tr>
        <tr class="cc debit">
            <td colspan="2" style="text-align: right">
                <b>City Ledger Name :</b>                                    
            </td>
            <td colspan="2" style="text-align: right">
                <?php
//                $user = User::model()->findAll();
//                $data = array(0 => t('choose', 'global')) + CHtml::listData($user, 'id', 'fullName');
                $id = isset($model->ca_user_id) ? $model->ca_user_id : 0;
                $selName = isset($model->CityLedger->name) ? $model->CityLedger->name : '';
                $this->widget(
                        'bootstrap.widgets.TbSelect2', array(
                    'asDropDownList' => false,
                    'name' => 'BillCharge[ca_user_id]',
                    'value' => $model->ca_user_id,
//                    'data' => $data,
                    'options' => array(
                        'allowClear' => true,
                        "placeholder" => t('choose', 'global'),
                        'width' => '85%',
                        'minimumInputLength' => '3',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('user/getListUser'),
                            'dataType' => 'json',
                            'data' => 'js:function(term, page) { 
                                                        return {
                                                            q: term 
                                                        }; 
                                                    }',
                            'results' => 'js:function(data) { 
                                                        return {
                                                            results: data
                                                        };
                                                    }',
                        ),
                        'initSelection' => 'js:function(element, callback) 
                            { 
                                data = {
                                    "id": ' . $id . ',
                                    "text": "' . $selName . '",
                                }
                                  callback(data);   
                            }',
                    ),
                        )
                );
                ?>                    
            </td>
            <td  style="text-align: right">
                <b>City Ledger :</b>                                    
            </td>
            <td style="text-align:right">
                <div class="input-prepend"><span class="add-on">Rp</span>
                    <?php echo Chtml::textField('BillCharge[ca_charge]', $model->ca_charge, array('class' => 'span2 angka')); ?>
                </div> 
            </td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: right">
                <b>Refund :</b>                                    
            </td>

            <td style="text-align:right">
                <div class="input-prepend"><span class="add-on">Rp</span>
                    <?php echo Chtml::textField('BillCharge[refund]', $model->refund, array('class' => 'span2 angka', 'readOnly' => true)); ?>
                </div>
            </td>
        </tr>                       
    </tbody>
</table>  

<script type="text/javascript">

    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type == "text")) {
            return false;
        }
    }

    document.onkeypress = stopRKey;
</script>