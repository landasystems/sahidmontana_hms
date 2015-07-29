<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'room-type-form',
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



        <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 45)); ?>        
        <?php echo $form->textFieldRow($model, 'pax', array('class' => 'span1 pax', 'maxlength' => 45)); ?>                
        <div class="control-group ">
            <label class="control-label" for="">Food & Baverage / Pax</label>                    
            <div class="controls">
                <div class="input-prepend ">
                    <span class="add-on">Rp</span>
                    <?php
                    $siteConfig = SiteConfig::model()->findByPk(1);
                    $settings = json_decode($siteConfig->settings, true);
                    $fnb = (!empty($settings['fb_charge'])) ? $settings['fb_charge'] : 0;
                    $fnb = (!empty($model->fnb_charge)) ? $model->fnb_charge : $fnb;
                    ?>
                    <input  class="fnb" name="RoomType[fnb_charge]" type="text" value="<?php echo $fnb; ?>">
                </div><br>
                <i>This field has been set at siteconfig</i>
            </div>
        </div>
        <?php
        echo $form->toggleButtonRow($model, 'is_package', array(
            'options' => array('enabledLabel' => 'Yes',
                'disabledLabel' => 'No',),
            'onChange' => '
                            if($("#RoomType_is_package").prop("checked")){
                            $(".package").show();
                            }else{
                            $(".package").hide();                            
                            }
                            '
        ));
        ?>
        <div class="control-group package" style="display: <?php echo ($model->is_package == 1) ? '' : 'none'; ?>">
            <label class="control-label" for="">Extra Package</label>                    
            <div class="controls">

                <table class="table table-striped table-bordered" style="margin-bottom: 0px">
                    <thead>
                        <tr>
                            <th style="width: 15px;text-align:center">#</th>
                            <th class="span4" style="text-align:center">Item Name</th>                             
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
                                        $text = '<i class="icon-plus-sign"></i>', $url = url('roomType/addRow'), $ajaxOptions = array(
                                    'type' => 'POST',
                                    'success' => 'function(data){                                       
                                                $("#addRow").replaceWith(data); 
                                                subtotal(0);
                                                clearField();
                                                totalRate();
                                                $(".delRow").on("click", function() {
                                                    $(this).parent().parent().remove();
                                                    subtotal(0);
                                                    totalRate();
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
                                        "placeholder" => 'Please Choose',
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
                                        $("#charge").val("");                                            
                                       }                          
                                       clearField();
                                       obj = JSON.parse(data);                                       
                                       $("#addDate").html(obj.date);
                                       $("#charge").val(obj.charge);  
                                       $("#addRoomNumber").html(obj.number);                                       
                                      
                                   }
                                });
                       }'),
                                        )
                                );
                                ?>                            
                            </td>                                                                                
                            <td style="text-align: center"><input type="text" maxlength="6" name="amount" id="amount" class="span1"/></td>                                                        
                            <td style="text-align: right" id="">
                                <div class="input-prepend">
                                    <span class="add-on">Rp</span>
                                    <input  type="text"  value="0" name="charge" id="charge">                    
                                </div> 
                            </td>                                                        
                            <td style="text-align: right" id="addSubtotal"></td>                                                        
                        </tr>                         
                        <?php
                        $totalPackage = 0;
                        if ($model->isNewRecord == FALSE) {
                            $package = json_decode($model->charge_additional_ids);
                            if (!empty($package)) {
                                foreach ($package as $data) {
                                    $additional = ChargeAdditional::model()->findByPk($data->id);
                                    echo
                                    '<tr class="items">
                                        <input type="hidden" name="detail[id][]" id="' . $data->id . '" value="' . $data->id . '"/>                        
                                        <input type="hidden" name="detail[amount][]" id="detQty" value="' . $data->amount . '"/>                        
                                        <input type="hidden" name="detail[charge][]" id="detCharge"  value="' . $data->charge . '"/>                                                    
                                        <input type="hidden" name="detail[total][]" id="detTotal" class="detTotal" value="' . $data->total . '"/>                                                    

                                        <td style="text-align:center"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></td>
                                        <td> &nbsp;&nbsp;&raquo; ' . $additional->name . '</td>
                                        <td style="text-align:center">' . $data->amount . '</td>' .
                                    '<td style="text-align:right">' . landa()->rp($data->charge) . '</td>                                                        
                                        <td style="text-align:right">' . landa()->rp($data->total) . '</td>                                                        
                                    </tr>';
                                    $totalPackage += (!empty($data->total)) ? $data->total : 0;
                                    ;
                                }
                            }
                        }
                        ?>





                        <tr id="addRow" style="display:none">          
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right">
                                <b>Grand Total :</b>                    
                                <input type="hidden" id ="BillCharge_total" name="BillCharge[total]" value="<?php echo $totalPackage; ?>" />
                            </td>
                            <td style="text-align:right"><span id="total"><?php echo landa()->rp($totalPackage); ?></span></td>                                                        
                        </tr>  
                    </tbody>
                </table>


            </div>        
        </div>

        <div class="control-group ">
            <label class="control-label" for="">Room Rate</label>                    
            <div class="controls">                    
                <table class="table table-condensed table-bordered" style="width:100%;margin-bottom: 0px">
                    <thead>
                        <tr>
                            <th class="span2" style="text-align: center;vertical-align: middle" rowspan="2">Guest Type</th>
                            <th rowspan="2" style="text-align: center;vertical-align: middle">Room Charge</th>
                            <th colspan="3" style="text-align: center;vertical-align: middle">Result Rate (Room Charge + F&B + Total Package)</th>

                        </tr>
                        <tr>                                                     
                            <th style="text-align: center;vertical-align: middle">Min</th>
                            <th style="text-align: center;vertical-align: middle">Default</th>
                            <th style="text-align: center;vertical-align: middle">Max</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $roles = User::model()->typeRoles('guest');
                        $rate = json_decode($model->rate, true);
                        foreach ($roles as $role => $value) {
                            $a = (isset($rate[$role]['roomRate'])) ? $rate[$role]['roomRate'] : 0;
                            $b = (isset($rate[$role]['min'])) ? $rate[$role]['min'] : 0;
                            $c = (isset($rate[$role]['default'])) ? $rate[$role]['default'] : 0;
                            $d = (isset($rate[$role]['max'])) ? $rate[$role]['max'] : 0;

                            echo'
                                    <tr>
                                    <td>' . $value . '</td>
                                    <td style="background:gainsboro"><div class="input-prepend ">
                                        <span class="add-on">Rp</span>
                                        <input style="width:77%" class="roomRate" name="' . $role . '[roomRate]" type="text" value="' . $a . '">                                        
                                    </div></td>
                                    <td><div class="input-prepend ">
                                        <span class="add-on">Rp</span>
                                        <input style="width:77%" class="minRate" id="appendedPrependedInput" name="' . $role . '[min]" type="text" value="' . $b . '">                                        
                                    </div></td>
                                    <td><div class="input-prepend ">
                                        <span class="add-on">Rp</span>
                                        <input style="width:77%" class="defaultRate" id="appendedPrependedInput" name="' . $role . '[default]" type="text" value="' . $c . '">
                                        
                                    </div></td>
                                    <td><div class="input-prepend ">
                                        <span class="add-on">Rp</span>
                                        <input  style="width:77%" class="maxRate" id="appendedPrependedInput" name="' . $role . '[max]" type="text" value="' . $d . '">                                        
                                    </div></td>
                                    </tr>
                                  ';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>                        
        <?php echo $form->textAreaRow($model, 'description', array('class' => 'span5', 'rows' => 3)); ?>

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
