<style>
    .modal.large {
        height: 90%; 
        width: 90%; 
        margin : -2% 0 0 -45%; 
    }

    .modal.large .modal-body{
        max-height: 700px;
    }
</style>
<script type="text/javascript">
    function getDetail(id) {
        var name = $("#Reservation_guest_user_id").val();
        //  alert(name);
        $.ajax({
            url: "<?php echo url('user/getDetail'); ?>",
            type: "POST",
            data: {id: id},
            success: function (data) {
                obj = JSON.parse(data);
                $("#id").val(obj.id);
                $("#group").val(obj.group);
                $("#roles").val(obj.group);
                $("#name").val(obj.name);
                $("#province_guest").val(obj.province);
                $("#city_guest").val(obj.city);
                $("#address").val(obj.address);
                $("#phone").val(obj.phone);
                $("#sex").val(obj.sex);
                $("#birth").val(obj.birth);
                $("#email").val(obj.email);
                $("#nationality").val(obj.nationality);
                $("#company").val(obj.company);
                $("#idCard").val(obj.number);
                $("#userNumber").val(obj.number);
                $("#nationality").trigger("change");
            }
        });
    }
    //$(document).ready(function () {
    //  $("#Reservation_guest_user_id").blur(function () {
    //setTimeout(getDetail(),30000);
    //    setTimeout(function () {
    //      getDetail()
    //}, 2000);
    //})
    //});
</script>
<div class="form">
    <style>        
        .control-label{width: 90px !important}
        .controls {margin-left:105px !important}

    </style>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'reservation-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    $minDate = date('d/m/Y');

    echo $form->hiddenField($model, 'id');
    ?>
    <?php
    foreach (Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="alert alert-danger">' . $message . "</div>\n";
    }
    ?>
    <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>       


    <ul class="nav nav-tabs" id="myTab">
        <li  class="active"><a href="#room">Room Information</a></li>  
        <li><a href="#guest">Guest Information</a></li>                                                                                  
        <li><a href="#dp">Deposite</a></li>     
        <li><a href="#booker">Booker Information</a></li>             
        <li><a href="#billing">Billing Instruction</a></li>                       
        <li><a href="#remarks">Remarks</a></li>                       
    </ul>
    <div class="tab-content">
        <div class="tab-pane" id="guest">
            <div class="box gradient invoice">
                <div class="title clearfix">
                    <h4 class="left">
                        <span class=" entypo-icon-user"></span>
                        <span>Guest Information</span>                        
                    </h4>
                    <div class="invoice-info">
                        <span class="number"> <strong class="red">
                                <?php
                                echo $model->code;
                                ?>
                            </strong></span>
                    </div>
                </div>
                <div class="content">   
                    <?php
                    echo $form->dropdownListRow($model, 'type', array('regular' => 'Regular', 'house use' => 'House Use', 'compliment' => 'Compliment'), array('style' => 'width:100%', 'onchange' => 'calculation()'));
                    ?>  
                    <div class="control-group ">
                        <label class="control-label">Market Seg.</label>
                        <div class="controls">                 
                            <input type="hidden" name="id" id="id" value="<?php echo $model->guest_user_id ?>">
                            <?php
                            $this->widget(
                                    'bootstrap.widgets.TbSelect2', array(
                                'name' => 'Reservation[market_segment_id]',
                                'value' => $model->market_segment_id,
                                'data' => MarketSegment::model()->Select2(),
                                'options' => array(
                                    'width' => '100%'
                            )));
                            ?>
                        </div>
                    </div>

                    <?php
                    $roles_id = '';
                    if ($model->isNewRecord == FALSE) {
                        $dateResult = date('d/m/Y', strtotime($model->date_from)) . ' - ' . date('d/m/Y', strtotime($model->date_to));
                        $roles_id = $model->Guest->roles_id;

                        $jsRemove = '';
                    } else {
                        $dateResult = date('d/m/Y', time()) . ' - ' . date('d/m/Y', time() + 86400);
                        $jsRemove = '';
                    }
//                    $data = array(0 => 'Please Choose') + CHtml::listData(User::model()->listUsers('guest'), 'id', 'fullName');
                    //$guestName = User::model()->listUsers('guest');
                    //$source = array();
                    //foreach ($guestName as $val) {
                    //    if (empty($val->company))
                    //        $source[] = $val->name;
                    //   else
                    //       $source[] = $val->name . ' ( ' . $val->company . ' )';
                    //}
                    if ($model->isNewRecord == FALSE) {
                        $guest = User::model()->findByPk($model->guest_user_id);
                        $model->guest_user_id = $guest->name;
                    }
                    ?>
                    <div class="control-group ">
                        <label class="control-label">Guest Name </label>
                        <div class="controls">
                            <?php
                            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                'name' => 'Reservation[guest_user_id]',
                                'sourceUrl' => array('registration/GetListGuest'),
                                'value' => $model->guest_user_id,
                                'options' => array(
                                    'showAnim' => 'fold',
                                    'minLength' => '3',
                                    'select' => 'js:function(event, ui){
                                        jQuery("#id").val(ui.item["item_id"]);
                                        jQuery("#name").val(ui.item["label"]);
                                        getDetail(ui.item["item_id"]);
                                    }'
                                ),
                            ))
                            ?>   
                        </div>
                    </div>
                    <hr>
                    <?php
                    $siteConfig = SiteConfig::model()->findByPk(1);
                    $group = "1";
                    $role = '1';
                    $name = "";
                    $province = "0";
                    $city = "0";
                    $totalRoom = 0;
                    $address = "";
                    $phone = "";
                    $email = "";
                    $idCard = "";
                    $company = '';
                    $sex = '';
                    $birth = '';
                    $nationality = '';
                    if ($model->isNewRecord == FALSE) {
                        $role = $model->Guest->roles_id;
                        $group = $model->Guest->roles_id;
                        $name = $model->Guest->name;
                        $email = $model->Guest->email;
                        $province = $model->Guest->City->Province->id;
                        $city = $model->Guest->City->id;
                        $address = $model->Guest->address;
                        $phone = $model->Guest->phone;
                        $idCard = $model->Guest->code;
                        $company = $model->Guest->company;
                        /* if (!empty($model->Guest->others)) {
                          $other = json_decode($model->Guest->others, true);
                          if (isset($other['company'])) {
                          $company = $other['company'];
                          }
                          } */
                        $phone = $model->Guest->phone;
                        $sex = $model->Guest->sex;
                        $birth = $model->Guest->birth;
                        $nationality = $model->Guest->nationality;
                    }
                    ?>
                    <table style="width:100%">
                        <tr>
                            <td style="vertical-align: top">                                
                                <div class="control-group ">
                                    <label class="control-label">ID Card</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('userNumber', $idCard, array('class' => 'span3', 'disabled' => false)); ?>
                                    </div>
                                </div>          
                                <div class="control-group ">
                                    <label class="control-label">Gender</label>
                                    <div class="controls">
                                        <?php echo CHtml::radioButtonList('sex', '', array('m' => 'Mr.', 'f' => 'Mrs.'), array('separator' => '')); ?>
                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">Name</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('name', $name, array('class' => 'span4', 'disabled' => false)); ?>
                                    </div>
                                </div>                                        
                                <div class="control-group ">
                                    <label class="control-label">Company</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('company', $company, array('class' => 'span4', 'disabled' => false,)); ?>
                                    </div>
                                </div>  
                                <div class="control-group ">
                                    <label class="control-label">Phone</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('phone', $phone, array('disabled' => false, 'class' => 'span4',)); ?>
                                    </div>
                                </div>                                                            
                            </td>
                            <td style="vertical-align: top">     
                                <div class="control-group ">
                                    <label class="control-label">Email</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('email', $email, array('disabled' => false, 'class' => 'span4',)); ?>
                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">Birth Day</label>
                                    <div class="controls">
                                        <div class="input-prepend"><span class="add-on"><i class="icon-calendar"></i></span>
                                            <?php
                                            $this->widget(
                                                    'bootstrap.widgets.TbDatePicker', array(
                                                'name' => 'birth',
                                                'options' => array('language' => 'en', 'format' => 'yyyy-mm-dd'),
                                                'htmlOptions' => array('class' => 'span2', 'disabled' => false)
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">Nationality</label>
                                    <div class="controls">
                                        <?php echo CHtml::dropDownList('nationality', $nationality, Province::model()->nationalityList, array('class' => 'span2', 'disabled' => false,)); ?>
                                    </div>
                                </div>
                                <?php $this->widget('common.extensions.landa.widgets.LandaProvinceCity', array('name' => 'guest', 'provinceValue' => $province, 'cityValue' => $city, 'disabled' => false,)); ?>                  
                                <div class="control-group ">
                                    <label class="control-label">Address</label>
                                    <div class="controls">
                                        <?php echo CHtml::textArea('address', $address, array('class' => 'span3', 'disabled' => false)); ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

            </div><!-- End .box -->
        </div>
        <div class="tab-pane active" id="room">
            <div class="box gradient invoice">
                <div class="title clearfix">
                    <h4 class="left">
                        <span class="wpzoom-clipboard-2"></span>
                        <span>Room Information</span>                        
                    </h4>
                    <div class="invoice-info">
                        <span class="number"> <strong class="red">
                                <?php
                                echo $model->code;
                                ?>
                            </strong></span>
                    </div>
                </div>
                <div class="content">
                    <table width="100%">
                        <tr>
                            <td style="vertical-align: top">

                                <?php
                                if ($model->isNewRecord == FALSE) {
                                    $date = date('d/m/Y', strtotime($model->date_from));
                                    $date2 = date('d/m/Y', strtotime($model->date_to));
                                } else {
                                    $date = date('d/m/Y', strtotime($siteConfig->date_system));
                                    $minDate = date('d/m/Y', strtotime($siteConfig->date_system));
                                    $date2 = date('d/m/Y', strtotime("+1 day", strtotime($siteConfig->date_system)));
                                }

                                if (!empty($_GET['date'])) {
                                    $date = date('d/m/Y', strtotime($_GET['date']));
                                    $date2 = date('d/m/Y', strtotime("+1 day", strtotime($_GET['date'])));
                                    $dateResult = $date . ' - ' . $date2;
                                }

                                echo $form->dateRangeRow(
                                        $model, 'date_from', array(
                                    'value' => $dateResult,
                                    'readOnly' => true,
                                    'prepend' => '<i class="icon-calendar"></i>',
                                    'options' => array(
                                        'format' => 'dd/MM/yyyy',
                                        'minDate' => $minDate,
                                        'callback' => 'js:function(start, end){console.log(start.toString("d MMMM, yyyy") + " - " + end.toString("d MMMM, yyyy"));changeDate(start.toString("yyyy-MM-dd"), end.toString("yyyy-MM-dd"));}',
                                        'startDate' => $date, 'endDate' => $date2),
                                        )
                                );
                                ?>

                                <div class="control-group ">
                                    <label class="control-label">Group</label>
                                    <div class="controls">                            
                                        <?php
                                        $array = User::model()->typeRoles('guest');
                                        echo CHtml::dropDownList('group', $group, $array, array(
                                            'class' => 'span3',
                                            'disabled' => false,
                                            'onchange' => '$("#roles").val(this.value)',
                                        ));
                                        ?>
                                        <input type="hidden" name="roles" id="roles" value="<?php echo $role; ?>"/>
                                    </div>
                                </div>

                                <div class="control-group ">
                                    <label class="control-label">Room Type</label>
                                    <div class="controls">
                                        <?php
                                        $roomType = array(0 => 'Please Choose') +
                                                CHtml::listData(RoomType::model()->findAll(array('condition' => 'is_package=0')), 'id', 'name');
                                        ?>
                                        <?php echo Chtml::dropdownList('roomType', '', $roomType, array('class' => 'span3',)); ?>
                                    </div>
                                </div> 
                            </td>

                            <td style="vertical-align: top">
                                <div class="control-group ">
                                    <label class="control-label">Bed</label>
                                    <div class="controls">                                        
                                        <?php echo Chtml::radioButtonList('bed', 0, array(0 => '-') + Room::model()->bedList, array('separator' => '')); ?>
                                    </div>
                                </div>                    
                                <div class="control-group ">
                                    <label class="control-label">Floor</label>
                                    <div class="controls">
                                        <?php
                                        $floor = array(0 => '-') + CHtml::listData(Room::model()->findAll(
                                                                array('group' => 't.floor')
                                                        ), 'floor', 'floor');
                                        ?>                                        
                                        <?php echo Chtml::radioButtonList('floor', 0, $floor, array('separator' => '')); ?>
                                    </div>
                                </div>   
                                <?php
                                $roomTypePackage = array(0 => 'Please Choose') + CHtml::listData(RoomType::model()->findAll(array('condition' => 'is_package=1')), 'id', 'name');
                                echo $form->dropdownListRow($model, 'package_room_type_id', $roomTypePackage, array('class' => 'span3'));
                                ?>    
                            </td>
                            <td style="text-align: right;vertical-align: top">                                
                                <?php
                                echo CHtml::ajaxSubmitButton('FIND ROOM', Yii::app()->createUrl('reservation/getRoom'), array(
                                    'type' => 'POST',
                                    'success' => 'js:function(data){ 
                                        if (data==""){
                                            alert("Room is empty");
                                        }else{
                                            $(".item").remove();                                            
                                            obj = JSON.parse(data);
                                            $("#addRow").replaceWith(obj.room);
                                            $("#statistik").replaceWith(obj.statistik);
                                            totalRoom();
                                        }                                        
                                    }'
                                        ), array('id' => 'btnFindRoom', 'class' => 'btn btn-primary', 'style' => 'height:120px;width:250px'));
                                ?>                                                     
                            </td>
                        </tr>
                    </table>

                    <div id="findRoom" class="modal large hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Find Available Room</h3>
                        </div>
                        <div class="modal-body">
                            <table class="items table  table-condensed">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>King</th>
                                        <th>Twin</th>
                                        <th>All</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="statistik" style="display:none">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div style="overflow:auto;max-height: 300px !important" class="well">
                                <table class="items table table-striped  table-condensed">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">Number</th>
                                            <th>Type</th>
                                            <th style="text-align:center">Floor</th>
                                            <th>Bed</th>
                                            <th>Rate</th>
                                            <th style="text-align:center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>                           
                                        <tr id="addRow" style="display:none">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>   
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                    </div>

                    <hr>
                    <div class="well">
                        <h2 style="text-align:center" class="blue">CHOOSED ROOM</h2> 
                        <hr>                           
                        <div class="detail_paket"></div>
                        <!--<div style="text-align:right"><a class="btn btn-small btn-primary" data-toggle="modal" data-target="#modalAuthority"><i class="icon-cog icon-white" style="margin:0px !important"></i> Change Price</a></div><br>-->

                        <div style="text-align:right">
                            <a class="btn btn-small btn-primary" data-toggle="modal" data-target="#modalAuthority"><i class="icon-cog icon-white" style="margin:0px !important"></i> Change Price</a>
                            <a class="btn btn-small btn-primary" data-toggle="modal" data-target="#modalTools"><i class="icon-edit icon-white" style="margin:0px !important"></i> Tools</a>
                        </div><br>

                        <table id="tb-choosed-room" class="items table table-striped  table-condensed">
                            <thead>
                                <tr>
                                    <th style="text-align:">Room</th>                                    
                                    <th>Mr. / Mrs.</th>
                                    <th>Pax</th>
                                    <th>FB Price</th>
                                    <th>EB</th>
                                    <th>EB Price</th>
                                    <th><input type="checkbox" id="all_others" class="all_others" style="margin:0px;padding:0px"> Others Include</th>
                                    <th>Room Charge</th>
                                    <th>Room Rate</th>
                                    <th style="text-align:center;width: 30px"></th>
                                </tr>
                            </thead>
                            <tbody>    
                                <?php
                                if ($model->isNewRecord == FALSE) {
                                    foreach ($mDetail as $detail) {
                                        $totalRoom++;
                                        $detail_include = json_decode($detail->others_include, true);
                                        $id = $detail->room_id;
                                        $checkbox_others_include = '';
                                        $checkbox_others_include_sys = '';

                                        if ($model->package_room_type_id == 0) {
                                            $rate = json_decode($detail->Room->RoomType->rate, true);
                                            $price = 'Min :' . landa()->rp($rate[$model->Guest->roles_id]['min']) . '<br> Default : ' .
                                                    landa()->rp($rate[$model->Guest->roles_id]['default']) . '<br> Max :' .
                                                    landa()->rp($rate[$model->Guest->roles_id]['max']);
                                        } else {
                                            $package_model = RoomType::model()->findByPk($model->package_room_type_id);
                                            $rate = json_decode($package_model->rate, true);
                                            $price = 'Min :' . landa()->rp($rate[$model->Guest->roles_id]['min']) . '<br> Default : ' .
                                                    landa()->rp($rate[$model->Guest->roles_id]['default']) . '<br> Max :' .
                                                    landa()->rp($rate[$model->Guest->roles_id]['max']);

                                            //menambah centangan
                                            $arrPackage = json_decode($package_model->charge_additional_ids);
                                            if (!empty($arrPackage)) {
                                                foreach ($arrPackage as $data) {
                                                    $additional = ChargeAdditional::model()->findByPk($data->id);
                                                    $nmPackage = '[ ' . $data->amount . ' x  @' . landa()->rp($data->charge, false) . '] ' . $additional->name . '<br>';
                                                    $totOi = ($data->amount * $data->charge );
                                                    $checked = (isset($detail_include[$additional->id])) ? 'checked' : '';
                                                    $checkbox_others_include.= $this->renderPartial('_oi', array('checked' => $checked, 'id' => $additional->id, 'room_id' => $detail->Room->id, 'val' => $totOi, 'name' => $nmPackage), true);
                                                }
                                            }
                                        }

                                        if ($siteConfig->others_include != "") {
                                            $others_include = json_decode($siteConfig->others_include);
                                            foreach ($others_include as $other) {
                                                $tuyul = ChargeAdditional::model()->findByPk($other);
                                                $checked = (isset($detail_include[$other])) ? 'checked' : '';
                                                if (count($tuyul) > 0) {
//                                                    $val = (isset($_POST["_" . $tuyul->id])) ? $_POST["_" . $tuyul->id] : 0;
                                                    $val = (isset($detail_include[$other])) ? $detail_include[$other] : $tuyul->charge;
                                                    $checkbox_others_include_sys.= $this->renderPartial('_oi', array('checked' => $checked, 'id' => $tuyul->id, 'room_id' => $detail->Room->id, 'val' => $val, 'name' => $tuyul->name), true);
                                                }
                                            }
                                        }



                                        $row = '';
                                        $row .= "<tr class='itemSelected' id='" . $id . "'>";
                                        $row .= "<td>";
                                        $row .= '<input type="hidden" value="' . $id . '" name="ReservationDetail[room_id][]" />';
                                        $row .= 'No : ' . $detail->Room->number . '<br>';
                                        $row .= 'Type : ' . $detail->Room->RoomType->name . ' - ' . ucfirst(strtolower($detail->Room->bed)) . '<br>';
                                        $row .= 'Floor : ' . $detail->Room->floor . '<br>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<textarea style="width:150px" rows="3" name="ReservationDetail[guest_user_names][]">' . $detail->guest_user_names . '</textarea>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<input style="width:30px" onChange="calculation()" class="pax" type="text" value="' . $detail->pax . '" name="ReservationDetail[pax][]" />';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= "<input type='hidden' name='ori_fnb_price' class='ori_fnb_price' value='" . $detail->Room->RoomType->fnb_charge . "' />";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" readOnly class="edit_price fnb_price" type="text" value="' . $detail->fnb_price . '" name="ReservationDetail[fnb_price][]" /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<input style="width:30px" onChange="calculation()" type="text" class="extrabed" value="' . $detail->extrabed . '" name="ReservationDetail[extrabed][]" />';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" readOnly class="edit_price extrabed_price" type="text" value="' . $detail->extrabed_price . '" name="ReservationDetail[extrabed_price][]" /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= $checkbox_others_include_sys;
                                        $row .= '<span class="pckg">' . $checkbox_others_include . '</span></td>';
                                        $row .= "<td>";
                                        $row .= "<input type='hidden' name='ori_rate' class='ori_rate' value='" . $rate[$model->Guest->roles_id]['default'] . "' />";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" class="room_rate edit_price" readOnly  type="text" value="' . $detail->room_price . '" name="ReservationDetail[room_price][]" /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" type="text" class="total_rate" value="' . $detail->charge . '" name="ReservationDetail[charge][]" readonly /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<a class="btn btn-small" onClick="$(this).parent().parent().remove();totalRoom();"  title="Remove" rel="tooltip" "><i class="cut-icon-minus-2"></i></a>';
                                        $row .= "</td>";
                                        $row .= "</tr>";
                                        $row .= '<tr id="selected" style="display:none"></tr>';
                                        echo $row;
                                    }
                                }

                                if (!empty($_GET['roomNumber']) && !empty($_GET['date'])) {
                                    $thisRoom = Room::model()->findByPk($_GET['roomNumber']);
                                    $usertype = 1; // hardcode for type GUEST
                                    if (!empty($thisRoom)) {
                                        if ($thisRoom->RoomType->is_package == 0) {
                                            $rate = json_decode($thisRoom->RoomType->rate, true);
                                            $price = 'Min :' . landa()->rp($rate[$usertype]['min']) . '<br> Default : ' .
                                                    landa()->rp($rate[$usertype]['default']) . '<br> Max :' .
                                                    landa()->rp($rate[$usertype]['max']);
                                        }

                                        $checkbox_others_include = "";
                                        if ($siteConfig->others_include != "") {
                                            $others_include = json_decode($siteConfig->others_include);
                                            foreach ($others_include as $other) {
                                                $tuyul = ChargeAdditional::model()->findByPk($other);
                                                $checked = '';
                                                if (count($tuyul) > 0) {
                                                    $val = $tuyul->charge;
                                                    $checkbox_others_include.= $this->renderPartial('_oi', array('checked' => $checked, 'id' => $tuyul->id, 'room_id' => $thisRoom->id, 'val' => $val, 'name' => $tuyul->name), true);
                                                }
                                            }
                                        }


                                        $row = '';
                                        $row .= "<tr class='itemSelected' id='" . $thisRoom->id . "'>";
                                        $row .= "<td>";
                                        $row .= '<input type="hidden" value="' . $thisRoom->id . '" name="ReservationDetail[room_id][]" />';
                                        $row .= 'No : ' . $thisRoom->number . '<br>';
                                        $row .= 'Type : ' . $thisRoom->RoomType->name . ' - ' . ucfirst(strtolower($thisRoom->bed)) . '<br>';
                                        $row .= 'Floor : ' . $thisRoom->floor . '<br>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<textarea style="width:150px" rows="3" name="ReservationDetail[guest_user_names][]"></textarea>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<input style="width:30px" onChange="calculation()" class="pax" type="text" value="2" name="ReservationDetail[pax][]" />';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= "<input type='hidden' name='ori_fnb_price' class='ori_fnb_price' value='" . $thisRoom->RoomType->fnb_charge . "' />";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" readOnly class="edit_price fnb_price" type="text" value="' . $thisRoom->RoomType->fnb_charge . '" name="ReservationDetail[fnb_price][]" /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<input style="width:30px" onChange="calculation()" type="text" class="extrabed" value="0" name="ReservationDetail[extrabed][]" />';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" readOnly class="edit_price extrabed_price" type="text" value="157500" name="ReservationDetail[extrabed_price][]" /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= $checkbox_others_include . '<span class="pckg"></span>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $roomPrice = $rate[$usertype]['default'] - ($thisRoom->RoomType->fnb_charge * $thisRoom->RoomType->pax);
                                        $row .= "<input type='hidden' name='ori_rate' class='ori_rate' value='" . $rate[$usertype]['default'] . "' />";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" class="room_rate edit_price" readOnly  type="text" value="' . $roomPrice . '" name="ReservationDetail[room_price][]" /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" type="text" class="total_rate" value="' . $rate[$usertype]['default'] . '" name="ReservationDetail[charge][]" readonly /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<a class="btn btn-small" onClick="$(this).parent().parent().remove();totalRoom();"  title="Remove" rel="tooltip" "><i class="cut-icon-minus-2"></i></a>';
                                        $row .= "</td>";
                                        $row .= "</tr>";
                                        $row .= '<tr id="selected" style="display:none"></tr>';
                                        echo $row;
                                    }
                                    $totalRoom = 1;
                                }
                                ?>                            
                                <tr id="selected" style="display:none">                            
                                </tr>
                            </tbody>
                            <thead>
                            <th colspan="10" style="text-align: center;background: lightsteelblue">Total Room Selected : <span id="totalRoom" class="label label-info"><?php echo $totalRoom; ?></span></th>
                            </thead>
                        </table>  
                    </div>                                        
                </div>
            </div>
        </div>
        <div class="tab-pane" id="booker">
            <div class="box gradient invoice">
                <div class="title clearfix">
                    <h4 class="left">
                        <span class=" entypo-icon-suitcase-2"></span>
                        <span>Booker Information</span>
                    </h4>    
                    <div class="invoice-info">
                        <span class="number"> <strong class="red">
                                <?php
                                echo $model->code;
                                ?>
                            </strong></span>
                    </div>
                </div>
                <div class="content">
                    <?php echo $form->dropdownListRow($model, 'reserved_by', array('' => 'Please Choose', 'Telephone' => 'Telephone', 'Fax' => 'Fax', 'EMail' => 'EMail', 'Website' => 'Website', 'Walk In' => 'Walk In'), array('disabled' => false)); ?>

                    <?php echo $form->textFieldRow($model, 'cp_name', array('disabled' => false, 'style' => 'width:100%', 'maxlength' => 45)); ?>

                    <?php echo $form->textFieldRow($model, 'cp_telephone_number', array('disabled' => false, 'style' => 'width:100%', 'maxlength' => 45)); ?>

                    <?php echo $form->textAreaRow($model, 'cp_note', array('disabled' => false, 'style' => 'width:100%;height:50px')); ?>
                </div>
            </div>
        </div>        
        <div class="tab-pane" id="billing">
            <div class="box gradient invoice">
                <div class="title clearfix">
                    <h4 class="left">
                        <span class="wpzoom-gamepad"></span>
                        <span>Billing Instruction</span>
                    </h4>

                    <div class="invoice-info">
                        <span class="number"> <strong class="red">
                                <?php
                                echo $model->code;
                                ?>
                            </strong></span>
                    </div> 
                </div>
                <div class="content">                       
                    <div class="billing" style="display:">
                        <?php
//                        $data = array(0 => 'Please Choose') + CHtml::listData(User::model()->listUsers('guest'), 'id', 'fullName');
                        $id = isset($model->billing_user_id) ? $model->billing_user_id : 0;
                        $billName = isset($model->Bill->name) ? '[' . $model->Bill->Roles->name . '] ' . $model->Bill->name : '';
                        echo $form->select2Row($model, 'billing_user_id', array(
                            'asDropDownList' => false,
//                            'data' => $data,
                            'value' => isset($model->billing_user_id) ? $model->billing_user_id : 0,
                            'options' => array(
                                'placeholder' => 'Please Choose',
                                'allowClear' => true,
                                'width' => '100%',
                                'minimumInputLength' => '3',
                                'ajax' => array(
                                    'url' => Yii::app()->createUrl('user/getBillUser'),
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
                                            "text": "' . $billName . '",
                                        }
                                          callback(data);   
                                    }',
                            ),
                                )
                        );
                        ?>
                        <?php echo $form->textAreaRow($model, 'billing_note', array('style' => 'width:100%;height:50px')); ?>                        

                    </div>

                </div>

            </div>
        </div>
        <div class="tab-pane" id="remarks">
            <?php
            echo $form->redactorRow(
                    $model, 'remarks', array(
                'label' => false,
                'options' => array(
                    'class' => 'span4',
                    'rows' => 10,
                    'options' => array('plugins' => array('clips', 'fontfamily'), 'lang' => 'sv')
                )
                    )
            );
            ?>
        </div>
        <div class="tab-pane" id="dp">
            <?php
            $dp_by = array('cash' => 'Cash', 'cc' => 'Credit Card', 'debit' => 'Debit Card');
            echo $form->textFieldRow($modelDp, 'code', array('class' => 'span3', 'disabled' => true));
            echo $form->dropDownListRow($modelDp, 'dp_by', $dp_by, array('class' => 'span2', 'maxlength' => 5));
            echo $form->textFieldRow($modelDp, 'amount', array('class' => 'span3', 'prepend' => 'Rp'));
            echo $form->textFieldRow($modelDp, 'cc_number', array('class' => 'span3'));
            echo $form->textAreaRow($modelDp, 'description', array('rows' => 6, 'cols' => 50, 'class' => 'span8'));
            ?>
        </div>
    </div>


    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => $model->isNewRecord ? 'Reservation' : 'Save Reservation',
        ));
        ?>
    </div>





    <?php
//change from charting
    $autoOpen = false;
    if (!empty($_GET['changeStatus'])) {
        $model->status = $_GET['changeStatus'];
        $autoOpen = true;
    }


    if ($model->isNewRecord == FALSE) {
        $this->beginWidget(
                'bootstrap.widgets.TbModal', array('id' => 'myModal', 'autoOpen' => $autoOpen)
        );
        ?>

        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <center><h4>STATUS RESERVATION</h4></center>
        </div>

        <div class="modal-body">
            <div class="row-fluid">
                <div class="span3">
                    Status
                </div>
                <div class="span1" style="width:10px">:</div>
                <div class="span8" style="text-align:left">
                    <?php
                    $readOnly = ($model->status != 'registered' or $model->status != 'cancel') ? false : true;
                    $array = ($model->status != 'registered') ? array('reservation' => 'Reservation', 'reserved' => 'Reserved', 'cancel' => 'Cancel', 'noshow' => 'No Show') : array('reservation' => 'Reservation', 'reserved' => 'Reserved', 'registered' => 'Registered', 'cancel' => 'Cancel', 'noshow' => 'No Show');
                    ?>
                    <?php echo $form->dropDownListRow($model, 'status', $array, array('disabled' => $readOnly, 'label' => false)); ?>
                </div>
            </div>        
            <div class="row-fluid">
                <div class="span3">
                    Reason if Cancel
                </div>
                <div class="span1" style="width:10px">:</div>
                <div class="span8" style="text-align:left">
                    <?php echo $form->textAreaRow($model, 'reason_of_cancel', array('style' => 'margin-bottom:5px;height:70px', 'class' => 'span12', 'label' => false)); ?>                    
                </div>
            </div> 
        </div>

        <div class="modal-footer">
            <button class="btn btn-primary"  type="submit" name="cancel">Save changes</button>
            <?php
            $this->widget(
                    'bootstrap.widgets.TbButton', array(
                'label' => 'Close',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
                    )
            );
            ?>
        </div>

        <?php
        $this->endWidget();
    }
    ?>



    <?php
    $this->beginWidget(
            'bootstrap.widgets.TbModal', array('id' => 'modalTools')
    );
    ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3>TOOLS EDIT VALUE</h3>
    </div>

    <div class="modal-body form-horizontal">

        <?php
        if ($model->isNewRecord == FALSE) {
            $chargeOtherInclude = json_decode($myDetail->others_include, true);
        }
        if ($siteConfig->others_include != "") {
            $others_include = json_decode($siteConfig->others_include);
            foreach ($others_include as $other) {
                $tuyul = ChargeAdditional::model()->findByPk($other);
                if (count($tuyul) > 0) {
                    $charge = (isset($chargeOtherInclude[$other])) ? $chargeOtherInclude[$other] : $tuyul->charge;
                    echo
                    '<div class="control-group ">
                                    <label class="control-label" for="">' . $tuyul->name . '</label>                                                            
                                    <div class="controls">
                                        <div class="input-prepend ">
                                            <span class="add-on">Rp</span>
                                            <input  class="editOtherInclude angka price-' . $tuyul->id . ' edit_price" kode="' . $tuyul->id . '" name="_' . $tuyul->id . '" type="text" value="' . $charge . '"  readonly="true">
                                        </div>                           
                                    </div>
                                </div>';
                }
            }
        }
        ?>
        <div class="control-group ">
            <label class="control-label" for="">Pax</label>                                                            
            <div class="controls">
                <input class="edit_price angka" type="text" value="0" style="max-width: 107px" readonly="true" id="txt_pax">
                <a style="margin-left: 10px" class="btn btn-small edit_price" disabled="true" id="btn_pax">Apply All</a>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="">FB Price</label>                                                            
            <div class="controls">
                <div class="input-prepend ">
                    <span class="add-on">Rp</span>
                    <input class="edit_price angka" type="text" value="0" readonly="true" id="txt_fb_price">
                    <a style="margin-left: 10px" class="btn btn-small edit_price" disabled="true" id="btn_fb_price">Apply All</a>
                </div>                           
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="">EB Price</label>                                                            
            <div class="controls">
                <div class="input-prepend ">
                    <span class="add-on">Rp</span>
                    <input class="edit_price angka" type="text" value="0" readonly="true" id="txt_eb_price">
                    <a style="margin-left: 10px" class="btn btn-small edit_price" disabled="true"  id="btn_eb_price">Apply All</a>
                </div>                           
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="">Room Charge</label>                                                            
            <div class="controls">
                <div class="input-prepend ">
                    <span class="add-on">Rp</span>
                    <input class="edit_price angka" type="text" value="0" readonly="true" id="txt_room_rate">
                    <a style="margin-left: 10px" class="btn btn-small edit_price" disabled="true"  id="btn_room_rate">Apply All</a>
                </div>                           
            </div>
        </div>
    </div>
    <div class="modal-footer" style="text-align: center"><span class="label label-warning">Activate "Change Price" before use this tools.</span></div>

    <?php $this->endWidget(); ?>

    <?php
    $this->beginWidget(
            'bootstrap.widgets.TbModal', array('id' => 'warningModal')
    );
    ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3>WARNING !</h3>
    </div>

    <div class="modal-body form-horizontal">
        <div class="alert alert-error"><div id="teks-warning"></div></div>
    </div>

    <div class="modal-footer">  
        <?php
        $this->widget(
                'bootstrap.widgets.TbButton', array(
            'label' => 'Close',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
                )
        );
        ?>
    </div>

    <?php $this->endWidget(); ?>
    <?php
    $this->beginWidget(
            'bootstrap.widgets.TbModal', array('id' => 'modalAuthority')
    );
    ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3>VERIFICATION AUTHORITY</h3>
    </div>

    <div class="modal-body form-horizontal">
        <div class = "control-group ">
            <label class = "control-label">Username</label>
            <div class="controls">                   
                <?php echo CHtml::textField('username', '', array('class' => 'span4')); ?>
                <?php echo CHtml::HiddenField('Reservation[approval_user_id]', $model->approval_user_id); ?>
            </div>
        </div>
        <div class = "control-group ">
            <label class = "control-label">Password</label>
            <div class="controls">
                <?php echo CHtml::passwordField('password', '', array('class' => 'span4')); ?>            
                <br>
                <br>
                <span class="text-error message"></span>
            </div>
        </div>
    </div>

    <div class="modal-footer">           
        <?php
        echo CHtml::ajaxLink(
                $text = '<button class="btn btn-primary">Submit</i></button>', $url = url('user/checkAuthority'), $ajaxOptions = array(
            'type' => 'POST',
            'success' => 'function(data){ 
                        obj = JSON.parse(data);                                                               
                        if (obj.user_id!=""){                            
                            $(".edit_price").attr("readonly", false);
                            $(".edit_price").attr("disabled", false);
                            $("#password").val("");  
                            $("#Reservation_approval_user_id").val(obj.user_id);
                            $("#modalAuthority").modal("hide");   
                        }else{
                            $(".message").html(obj.message);
                            $("#password").val("");
                        }
                      }'), $htmlOptions = array()
        );
        ?> 
        <?php
        $this->widget(
                'bootstrap.widgets.TbButton', array(
            'label' => 'Close',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
                )
        );
        ?>
    </div>

    <?php $this->endWidget(); ?>
    <?php $this->endWidget(); ?>
</div>


<script>
    function calculation() {

        $(".pax").each(function () {
            var pax = parseInt($(this).val());
            pax = pax ? pax : 0;
            var bed = parseInt($(this).parent().parent().find(".extrabed").val());
            var bed_price = parseInt($(this).parent().parent().find(".extrabed_price").val());
            var fnb = parseInt($(this).parent().parent().find(".fnb_price").val());
            var room_rate = parseInt($(this).parent().parent().find(".room_rate").val());
            room_rate = room_rate ? room_rate : 0;
            bed = bed ? bed : 0;
            fnb = fnb ? fnb : 0;
            bed_price = bed_price ? bed_price : 0;
            var rowId = $(this).parent().parent().attr('id');
            var other = 0;
            $(".others_include").each(function () {
                var thisRowId = $(this).attr('r');
                if (rowId == thisRowId) {
                    if (this.checked) {
                        other += parseInt($(this).val());
                    }
                }
            });
            var type = $("#Reservation_type").val();
            var price_default = (fnb * pax) + (bed * bed_price) + room_rate + other;
            $(this).parent().parent().find(".total_rate").val(price_default);
        });
    }
    $("#Reservation_package_room_type_id").on("change", function () {
        if ($(this).val() == 0) {
            $(".detail_paket").html('');
            $(".pckg").html('');
            calculation();
        } else {
            $.ajax({
                url: "<?php echo url('reservation/getPackage'); ?>",
                type: "POST",
                data: $('form').serialize(),
                success: function (data) {
                    $(".detail_paket").html(data);
                    data = $('#detPackage').val();
                    data = JSON.parse(data);
                    data2 = $('#pricePackage').val();
                    data2 = JSON.parse(data2);
                    $(".pckg").each(function () {
                        a = this;
                        result = '';
                        $.each(data, function (i, n) {
                            room_id = $(a).parent().parent().find('.room_id').val();
                            result += '<label><input checked class="others_include ' + n['id'] + '" kode="' + n['id'] + '" style="margin:0px 5px 0px 0px" type="checkbox" r="' + room_id + '" name="others_include[' + room_id + '][' + n['id'] + ']"  value="' + n['total'] + '">' + n['name'] + '</label>';
                        });
                        $(a).html(result);
                    });
                    // baru diCreatekan
                    $(".room_rate").each(function () {
                        b = this;
                        result = '';
                        //$.each(data, function (i, n) {
                        room_id = $(a).parent().parent().find('.room_id').val();
                        result += data2['charge'];
                        //});
                        $(b).val(result);
                    });
                    $(".pax").each(function () {
                        b = this;
                        result = '';
                        $.each(data, function (i, n) {
                            room_id = $(a).parent().parent().find('.room_id').val();
                            result += n['pax'];
                        });
                        $(b).val(result);
                    });
                    $(".fnb_price").each(function () {
                        b = this;
                        result = '';
                        //$.each(data, function (i, n) {
                        room_id = $(a).parent().parent().find('.room_id').val();
                        result += data2['fnb'];
                        //});
                        $(b).val(result);
                    });
                    //
                    calculation();
                }
            });
        }
    })
    $("#btn_pax").on("click", function () {
        var disabled = $(this).attr("disabled") || 0;
        if (disabled == 0) {
            var nilai = $("#txt_pax").val();
            $(".pax").val(nilai);
            calculation();
        }
    });
    $("#btn_eb_price").on("click", function () {
        var disabled = $(this).attr("disabled") || 0;
        if (disabled == 0) {
            var nilai = $("#txt_eb_price").val();
            $(".extrabed_price").val(nilai);
            calculation();
        }
    });
    $("#btn_fb_price").on("click", function () {
        var disabled = $(this).attr("disabled") || 0;
        if (disabled == 0) {
            var nilai = $("#txt_fb_price").val();
            $(".fnb_price").val(nilai);
            calculation();
        }
    });
    $("#btn_room_rate").on("click", function () {
        var disabled = $(this).attr("disabled") || 0;
        if (disabled == 0) {
            var nilai = $("#txt_room_rate").val();
            $(".room_rate").val(nilai);
            calculation();
        }
    });
    function totalRoom() {
        var total = $(".itemSelected").length;
        $("#totalRoom").html(total);
    }

    $('#nationality').on('change', function () {
        if ($(this).val() == 'ID') {
            $('#s2id_city_guest').show();
        } else {
            $('#s2id_city_guest').hide();
        }
    })

    $('#btnFindRoom').on('click', function () {
        $('#findRoom').modal('show');
    })

    function clearItem() {
        $(".itemSelected").remove();
        $("#totalRoom").html("0");
    }

    function changeDate(start, end) {
        $.ajax({
            url: "<?php echo url('reservation/checkRoom'); ?>",
            type: "POST",
            data: $('form').serialize(),
            success: function (data) {
                if (data != '') {
                    $('button[type="submit"]').attr('disabled', 'disabled');
                    $("#teks-warning").html(data);
                    $('#warningModal').modal('show');
                } else {
                    $('button[type="submit"]').removeAttr('disabled');
                }
            }
        });
    }
</script>
