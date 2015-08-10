<?php
$this->setPageTitle('Move Room');

$siteConfig = SiteConfig::model()->findByPk(1);

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
<div class="box  invoice">
    <div class="title clearfix">
        <h4 class="left">
            <span class="blue cut-icon-bookmark"></span>
            <span>Move Room Detail Form</span>                        
        </h4>
        <div class="invoice-info">
            <span class="number"> <strong class="red">
                </strong></span>
        </div> 
    </div>

    <div class="content" style="padding-left: 0px !important;padding-right: 0px !important">  
        <?php
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<div class="alert alert-' . $key . '" style="margin-left:10px;margin-right:10px;">' . $message . '</div>';
        }
        ?>
        <table style="width:100%">
            <tr><td style="vertical-align: top">

                    <div class="control-group ">
                        <label class="control-label" for="Reservation_guest_user_id">Find Room :</label>
                        <div class="controls">
                            <?php
                            $data = array(0 => 'Please Choose') + CHtml::listData($room, 'id', 'fullRoom');
                            $this->widget(
                                    'bootstrap.widgets.TbSelect2', array(
                                'asDropDownList' => true,
                                'name' => 'roomId',
                                'data' => $data,
                                'value' => (!empty($number)) ? $number : '',
                                'options' => array(
                                    "placeholder" => 'Please Choose',
                                    "allowClear" => true,
                                    'width' => '50%',
                                ),
                                    )
                            );
                            ?>
                        </div>
                    </div>
                    <hr>
                    <div class="alert alert-warning hide alert2">
                        <h4 class="alert-heading">Ups! Room must be Checkout this day.</h4>
                        <p>To move this guest to another Room, please <b>Extend</b> this room first</p>
                        <p>
                            <a class="btn btn-info" href="<?php echo url('roomBill/extend') ?>">Extend</a>
                        </p>
                    </div>

                    <table style="width:100%" class="tbInfo">
                        <tr>
                            <td class="span8" >
                                <table>
                                    <tr>
                                        <td class="span2">Reg. Code</td>
                                        <td style="width:1px">:</td>
                                        <td class="span2"><span id="code"><?php echo (!empty($regCode)) ? $regCode : ''; ?></span></td>   

                                        <td class="span2">Room Number</td>
                                        <td style="width:1px">:</td>
                                        <td class="span2"><span id="number"><?php echo (!empty($number)) ? $number : ''; ?></span></td> 


                                        <td class="span2">Room Floor</td>
                                        <td style="width:1px">:</td>
                                        <td class="span2"><span id="roomFloor"><?php echo (!empty($roomFloor)) ? $roomFloor : ''; ?></span></td>      

                                    </tr>                                    
                                    <tr>
                                        <td >Mr. / Mrs.</td>
                                        <td style="width:1px">:</td>
                                        <td ><span id="name"><?php echo (!empty($guest_name)) ? $guest_name : ''; ?></span></td> 

                                        <td >Room Bed</td>
                                        <td style="width:1px">:</td>
                                        <td ><span id="roomBed"><?php echo (!empty($roomBed)) ? $roomBed : ''; ?></span></td>    

                                        <td>Check In</td>
                                        <td>:</td>
                                        <td><span id="date_to"><?php echo (!empty($checkIn)) ? $checkIn : ''; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td >Customer Type</td>
                                        <td style="width:1px">:</td>
                                        <td ><span id="customerType"><?php echo (!empty($guest_name)) ? $guest_name : ''; ?></span></td>  

                                        <td >Room Type</td>
                                        <td style="width:1px">:</td>
                                        <td ><span id="roomType"><?php echo (!empty($roomType)) ? $roomType : ''; ?></span></td> 

                                        <td>Check Out</td>
                                        <td>:</td>
                                        <td>
                                            <span id="check_out"><?php echo (!empty($checkOut)) ? $checkOut : ''; ?></span>                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>     
                        </tr>
                    </table>          

                </td>                
            </tr>
        </table>
        <hr>
        <div class="well">
            <table>
                <tr>
                    <td>
                        <label>Date Move :</label> 
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                            <input disabled="true" class="span2" type="text" autocomplete="off" value="<?php echo date('m/d/Y', strtotime($siteConfig->date_system)); ?>" name="date_move1" id="date_move1">
                            <input type="hidden" value="<?php echo date('m/d/Y', strtotime($siteConfig->date_system)); ?>" name="date_move" id="date_move" class="date_move" />
                            <input type="hidden" value="" name="date_check_out" id="date_check_out" class="date_check_out" />
                        </div> </td>
                    <td>
                        <label>Root Type :</label>  
                        <?php
                        $roomType = array(0 => 'Please Choose') +
                                CHtml::listData(RoomType::model()->findAll(array('condition' => 'is_package=0')), 'id', 'name');
                        ?>
                        <?php echo Chtml::dropdownList('roomType', '', $roomType, array('class' => '',)); ?>
                    </td>
                    <td>
                        <label>Bed :</label> 
                        <?php echo Chtml::dropdownList('bed', '', array('' => 'Please Choose', 'Single' => 'Single', 'Twin' => 'Twin'), array('class' => '',)); ?>
                    </td>
                    <td>

                        <label>Floor :</label> 
                        <?php
                        $floor = array(0 => 'Please Choose') +
                                CHtml::listData(Room::model()->findAll(
                                                array('group' => 't.floor')
                                        ), 'floor', 'floor');
                        ?>        
                        <?php echo Chtml::dropdownList('floor', '', $floor, array('class' => '',)); ?>  
                    </td>
                    <td>
                        <br>
                        <?php
                        echo CHtml::ajaxSubmitButton('Search', Yii::app()->createUrl('roomBill/getRoom'), array(
                            'type' => 'POST',
                            'success' => 'js:function(data){ 
                                        if (data!="")
                                       $(".selectRoom").replaceWith(data);
                                    }'
                                ), array('class' => 'btn btn-primary',));
                        ?>
                    </td>
                </tr>
            </table>


        </div>

        <hr>

        <table class="selectRoom table table-striped  table-condensed">
            <thead>
                <tr>
                    <th class="span1" style="text-align:center">Number</th>
                    <th class="span1">Type</th>
                    <th class="span1" style="text-align:center">Floor</th>
                    <th class="span1">Bed</th>                    
                    <th class="span2">FB Price</th>
                    <th class="span2">EB Price</th>
                    <th class="span2">Other Includes</th>                    
                    <th class="span2">Room Rate</th>
                    <th style="text-align:center;width: 10px"></th>
                </tr>
            </thead>
            <tbody>                              
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
        'label' => 'Move Room',
        'id' => 'btnMove',
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
    $("#roomId").on("change", function() {
        $.ajax({
            url: "<?php echo url('roomBill/getRegistration'); ?> ",
            type: "POST",
            data: {regID: $(this).val()},
            success: function(data) {
                $(".items").remove();
                if (data == "extend") {
                    $(".tbInfo").hide();
                    $("#btnMove").prop("disabled", true);
                    $(".alert2").show()
                } else {
                    $(".tbInfo").show();
                    $("#btnMove").prop("disabled", false);
                    $(".alert2").hide()

                    obj = JSON.parse(data);
                    $("#number").html(obj.number);
                    $("#roomNumber").val(obj.number);
                    $("#customerType").html(obj.customerType);
                    $("#roomType").html(obj.roomType);
                    $("#roomBed").html(obj.roomBed);
                    $("#roomFloor").html(obj.roomFloor);
                    $("#name").html(obj.name);
                    $("#date_to").html(obj.date_to);
                    $("#addRow").replaceWith(obj.detail);
                    $("#total").html(obj.total);
                    $("#grandTotal").val(obj.grandTotal);
                    $("#idRoomBill").val(obj.idRoomBill);
                    $("#code").html(obj.reg_code);
                    $("#check_out").html(obj.check_out);
                    $("#date_extend").val(obj.date_check_out);
                    $("#date_check_out").val(obj.date_check_out);
                }
            }
        });
    });
    $("#roomId").trigger("change");
</script>