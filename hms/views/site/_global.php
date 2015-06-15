<legend>
    <p class="note">Fields with <span class="required">*</span> is Required.</p>
</legend>
<?php
$settings = json_decode($model->settings, true);
$extrabed = (!empty($settings['extrabed_charge'])) ? $settings['extrabed_charge'] : '';
$fnb = (!empty($settings['fb_charge'])) ? $settings['fb_charge'] : '';
$fnbAccount = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';
$roomAccount = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
$rateDolar = (!empty($settings['rate'])) ? $settings['rate'] : 0;
$tax = (!empty($settings['tax'])) ? $settings['tax'] : 0;
$model->date_system = date("Y-m-d");

echo $form->datepickerRow(
        $model, 'date_system', array(
    'options' => array('language' => 'en', 'format' => 'yyyy-mm-dd'),
    'prepend' => '<i class="icon-calendar"></i>'
        )
);
?>
<div class="control-group "><label class="control-label" for="">Rate US</label>
    <div class="controls">
        <div class="input-prepend">
            <span class="add-on">$</span>
            <input class="span2" maxlength="255" name="rate" value="<?php echo $rateDolar ?>" id="rate" type="text">                            
        </div>
    </div>
</div>
<div class="control-group "><label class="control-label" for="">Charge Extrabed</label>
    <div class="controls">
        <div class="input-append input-prepend">
            <span class="add-on">Rp</span>
            <input class="span2" maxlength="255" name="extrabed" value="<?php echo $extrabed ?>" id="extrabed" type="text">
            <span class="add-on">/ Pax</span>
        </div>
    </div>
</div>
<div class="control-group "><label class="control-label" for="">Charge Breakfast</label>
    <div class="controls">
        <div class="input-append input-prepend">
            <span class="add-on">Rp</span>
            <input class="span2" maxlength="255" name="fnb" id="fnb" type="text" value="<?php echo $fnb; ?>">
            <span class="add-on">/ Pax</span>
        </div>
    </div>
</div>  
<div class="control-group "><label class="control-label" for="">Breakfast Account</label>
    <div class="controls">
        <?php
        $account = Account::model()->findAll();
        $dataAccount = CHtml::listData($account, 'id', 'name');
        $this->widget(
                'bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => true,
            'name' => 'breakfastAccount',
            'data' => $dataAccount,
            'value' => $fnbAccount,
            'options' => array(
                "placeholder" => t('choose', 'global'),
                "allowClear" => false,
                'width' => '30%;margin:0px',
        )));
        ?> 
    </div>
</div>  
<div class="control-group "><label class="control-label" for="">Room Charge Account</label>
    <div class="controls">
        <?php
        $this->widget(
                'bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => true,
            'name' => 'roomAccount',
            'data' => $dataAccount,
            'value' => $roomAccount,
            'options' => array(
                "placeholder" => t('choose', 'global'),
                "allowClear" => false,
                'width' => '30%;margin:0px',
        )));
        ?> 
    </div>
</div>     
<div class="control-group">
    <label class="control-label" for="">Others Include</label>                    
    <div class="controls">
        <table class="table table-striped table-bordered" style="margin-bottom: 0px">
            <thead>
                <tr>
                    <th style="width: 15px;text-align:center">#</th>
                    <th class="span4" style="text-align:center">Name</th>                                                                 
                    <th class="span2" style="text-align:center">Price</th>                                                                                                
                </tr>
            </thead>
            <tbody>     
                <tr class="hidePrint">
                    <td style="text-align:center">
                        <?php
                        echo CHtml::ajaxLink(
                                $text = '<button><i class="icon-plus-sign"></i></button>', $url = url('siteConfig/addRow'), $ajaxOptions = array(
                            'type' => 'POST',
                            'success' => 'function(data){                                       
                                                $("#addRow").replaceWith(data); 
                                                $(".delRow").on("click", function() {
                                                    $(this).parent().parent().remove();                                                  
                                                });
                                                $("#addPrice").html("");
                                            
                                        }'), $htmlOptions = array()
                        );
                        ?>                        
                    </td>
                    <td>                        
                        <?php
                        $data2 = !empty(ChargeAdditionalCategory::model()->findAll()) ? RoomBill::model()->getAdditional() : array(0 => 'please insert charge additional before');

                        $this->widget(
                                'bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => true,
                            'name' => 'additional_id',
                            'data' => $data2,
                            'options' => array(
                                "placeholder" => t('choose', 'global'),
                                "allowClear" => false,
                                'width' => '100%;margin:0px',
                            ),
                            'htmlOptions' => array(
                                'id' => 'additional_id',
                            ),
                            'events' => array('change' => 'js: function() {
                                                            $.ajax({
                                                               url : "' . url('roomBill/getAdditional') . '",
                                                               type : "POST",
                                                               data :  { addID:$(this).val()},
                                                               success : function(data){
                                                                obj = JSON.parse(data);                
                                                                   if (data==""){
                                                                    $("#addPrice").html("");
                                                                   }                                                                                             
                                                                   $("#addPrice").html(obj.charge);                                                                     

                                                               }
                                                            });
                                                   }'),
                                )
                        );
                        ?>                            
                    </td>                                                                                                                 
                    <td style="text-align: right" id="addPrice"></td>
                </tr>   
                <?php
                $others_include = json_decode($model->others_include);
                if (!empty($others_include)) {
                    foreach ($others_include as $other) {
                        $charge = ChargeAdditional::model()->findByPk($other);
                        if (count($charge) > 0) {
                            echo '                                                  
                                        <tr class="items">
                                            <input type="hidden" name="others_include[]" id="' . $charge->id . '" value="' . $model->id . '"/>                                                                                                    
                                            <td style="text-align:center"><button class="delRow"><i class="icon-remove-circle" style="cursor:all-scroll;"></i></button></td>
                                            <td> &nbsp;&nbsp;&raquo; ' . $charge->name . '</td>                        
                                            <td style="text-align:right">' . landa()->rp($charge->charge) . '</td>                                                        
                                        </tr>                     
                                        ';
                        }
                    }
                }
                ?>
                <tr id="addRow" style="display:none"></tr>
            </tbody>
        </table>                        
    </div>        
</div>
<script>
    $(".delRow").on("click", function () {
        $(this).parent().parent().remove();
    });
</script>