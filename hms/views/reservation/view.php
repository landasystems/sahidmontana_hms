<?php
$this->setPageTitle('View Reservations | ID : ' . $model->id);

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
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
        //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
)));
$this->endWidget();
?>


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
    $minDate = date('m/d/Y');
    ?>
    <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>       


    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#guest">Guest Information</a></li>                                                     
        <li><a href="#room">Room Information</a></li>  
        <li><a href="#dp">Deposite</a></li>   
        <li><a href="#booker">Booker Information</a></li>                       
        <li><a href="#billing">Billing Instruction</a></li>                       
        <li><a href="#remarks">Remarks</a></li>                       
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="guest">
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
                    echo $form->dropdownListRow($model, 'type', array('regular' => 'Regular', 'house use' => 'House Use / Compliment'), array('disabled' => true, 'class' => 'span9', 'onchange' => 'calculation()'));
                    ?>
                    <div class = "control-group ">
                        <label class = "control-label">Market Seg.</label>
                        <div class = "controls">
                            <?php echo CHtml::dropDownList('Reservation[market_segment_id]', $model->market_segment_id, CHtml::listData(MarketSegment::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname'), array('disabled' => true, 'class' => 'span9', 'empty' => 'Please Choose'));
                            ?>
                        </div>
                    </div> 
                    <?php
                    $dateResult = '';
                    $roles_id = '';
                    if ($model->isNewRecord == FALSE) {
                        $dateResult = date('d/m/Y', strtotime($model->date_from)) . ' - ' . date('d/m/Y', strtotime($model->date_to));
                        $roles_id = $model->Guest->roles_id;
                    }
                    ?>


                    <?php
                    
                    $data = array(0 => 'Please Choose') + CHtml::listData(User::model()->listUsers('guest'), 'id', 'fullName');
                    ?>

                    <?php
                    $group = "0";
                    $name = "";
                    $province = "0";
                    $city = "0";
                    $address = "";
                    $phone = "";
                    $idCard = "";
                    $company = '';
                    $sex = '';
                    $birth = '';
                    $nationality = '';
                    if ($model->isNewRecord == FALSE) {
                        $group = $model->Guest->roles_id;
                        $name = $model->Guest->name;
                        $province = $model->Guest->City->Province->id;
                        $city = $model->Guest->City->id;
                        $address = $model->Guest->address;
                        $email = $model->Guest->email;
                        $phone = $model->Guest->phone;
                        $idCard = $model->Guest->code;
                        if (!empty($model->Guest->others)) {
                            $other = json_decode($model->Guest->others, true);
                            if (isset($other['company'])) {
                                $company = $other['company'];
                            }
                        }
                        $phone = $model->Guest->phone;
                        $sex = $model->Guest->sex;
                        $birth = $model->Guest->birth;
                        $nationality = $model->Guest->nationality;
                    }
                    ?>
                    <table>
                        <tr>
                            <td style="vertical-align: top">
                                <div class="control-group ">
                                    <label class="control-label">Group</label>
                                    <div class="controls">                            
                                        <?php
                                        $array = User::model()->typeRoles('guest');
                                        echo CHtml::dropDownList('group', $group, $array, array(
                                            'empty' => 'Please Choose',
                                            'class' => 'span3',
                                            'disabled' => true,
                                            'onchange' => '$("#roles").val(this.value)',
                                        ));
                                        ?>
                                        <input type="hidden" name="roles" id="roles" value="<?php echo $roles_id; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">ID Card</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('userNumber', $idCard, array('class' => 'span4', 'disabled' => true)); ?>
                                    </div>
                                </div>                    
                                <div class="control-group ">
                                    <label class="control-label">Gender</label>
                                    <div class="controls">
                                        <?php echo CHtml::dropDownList('sex', '', array('m' => 'Mr.', 'f' => 'Mrs.'), array('disabled' => true)); ?>
                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">Name</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('name', $name, array('class' => 'span4', 'disabled' => true)); ?>
                                    </div>
                                </div>                                        
                                <div class="control-group ">
                                    <label class="control-label">Company</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('company', $company, array('class' => 'span4', 'disabled' => true,)); ?>
                                    </div>
                                </div>  
                                <div class="control-group ">
                                    <label class="control-label">Phone</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('phone', $phone, array('disabled' => true, 'class' => 'span4',)); ?>
                                    </div>
                                </div>
                            </td>
                            <td style="vertical-align: top">
                                <div class="control-group ">
                                    <label class="control-label">Email</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('email', $email, array('disabled' => true, 'class' => 'span4',)); ?>
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
                                                'htmlOptions' => array('class' => 'span3', 'disabled' => true)
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group ">
                                    <label class="control-label">Nationality</label>
                                    <div class="controls">
                                        <?php echo CHtml::textField('nationality', $nationality, array('class' => 'span4', 'disabled' => true,)); ?>
                                    </div>
                                </div>
                                <?php $this->widget('common.extensions.landa.widgets.LandaProvinceCity', array('name' => 'guest', 'provinceValue' => $province, 'cityValue' => $city, 'disabled' => true,)); ?>                  
                                <div class="control-group ">
                                    <label class="control-label">Address</label>
                                    <div class="controls">
                                        <?php echo CHtml::textArea('address', $address, array('class' => 'span4', 'disabled' => true)); ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

            </div><!-- End .box -->
        </div>
        <div class="tab-pane" id="room">
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
                                    $date = date('m/d/Y', strtotime($model->date_from));
                                    $date2 = date('m/d/Y', strtotime($model->date_to));
                                } else {
                                    $date = date('m/d/Y');
                                    $date2 = date('m/d/Y');
                                }


                                if (!empty($_GET['date'])) {
                                    $date = date('m/d/Y', strtotime($_GET['date']));
                                    $date2 = date('m/d/Y', strtotime("+1 day", strtotime($_GET['date'])));
                                    $dateResult = $date . ' - ' . $date2;
                                }
                                echo $form->dateRangeRow(
                                        $model, 'date_from', array(
                                    'value' => $dateResult,
                                    'prepend' => '<i class="icon-calendar"></i>',
                                    'disabled' => true,
                                    'options' => array(
                                        'minDate' => $minDate,
                                        'callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}',
                                        'startDate' => $date, 'endDate' => $date2),
                                        )
                                );
                                ?>

                                <?php
                                $roomTypePackage = array(0 => 'Please Choose') + CHtml::listData(RoomType::model()->findAll(array('condition' => 'is_package=1')), 'id', 'name');
                                echo $form->dropdownListRow($model, 'package_room_type_id', $roomTypePackage, array('disabled' => true, 'class' => 'span3', 'ajax' => array('type' => 'POST', 'url' => url('reservation/getPackage'), 'success' => 'function (data){                                
                                    $(".detail_paket").html(data);                                    
                                 }')));
                                ?>        
                            </td>                            
                        </tr>

                    </table>
                    <div class="detail_paket" ></div>
                    <hr>
                    <div class="well">
                        <h2>Choosed Room</h2>
                        <?php
                        $siteConfig = SiteConfig::model()->findByPk(1);
                        $settings = json_decode($siteConfig->settings, true);
                        $fnb = (!empty($settings['fb_charge'])) ? $settings['fb_charge'] : 0;
                        $exbed = (!empty($settings['extrabed_charge'])) ? $settings['extrabed_charge'] : 0;
                        ?>
                        <hr>

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
                                            <input  class="editOtherInclude price-' . $tuyul->id . ' edit_price" kode="' . $tuyul->id . '" name="_' . $tuyul->id . '" type="text" value="' . $charge . '"  readonly="true">
                                        </div>                           
                                    </div>
                                </div>';
                                }
                            }
                        }
                        ?>
                        <hr>

                        <table class="items table table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th style="text-align:">Room</th>                                    
                                    <th>Mr. / Mrs.</th>
                                    <th>Pax</th>
                                    <th>FB Price</th>
                                    <th>EB</th>
                                    <th>EB Price</th>
                                    <th>Others Include</th>
                                    <th>Room Charge</th>
                                    <th>Room Rate</th>                                   
                                </tr>
                            </thead>
                            <tbody>    
                                <?php
                                if ($model->isNewRecord == FALSE) {
                                    foreach ($mDetail as $detail) {
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
                                        }

                                        $checkbox_others_include = ""; //                                       
                                        if ($siteConfig->others_include != "") {
                                            $others_include = json_decode($siteConfig->others_include);
                                            $detail_include = json_decode($detail->others_include, true);
                                            foreach ($others_include as $other) {
                                                $tuyul = ChargeAdditional::model()->findByPk($other);
                                                $checked = (!empty($detail_include[$other])) ? 'checked' : '';
                                                if (count($tuyul) > 0) {
                                                    $val = (isset($_POST["_" . $tuyul->id])) ? $_POST["_" . $tuyul->id] : 0;
                                                    $checkbox_others_include.='<label><input disabled ' . $checked . ' class="others_include ' . $tuyul->id . '" kode="' . $tuyul->id . '" style="margin:0px 5px 0px 0px" type="checkbox" name="others_include[' . $detail->Room->id . '][' . $tuyul->id . ']"  value="' . $val . '">' . $tuyul->name . '</label>';
                                                }
                                            }
                                        }

                                        $usertype = $model->Guest->roles_id;
                                        $id = $detail->room_id;
                                        $row = '';
                                        $row .= "<tr id='" . $id . "'>";
                                        $row .= "<td>";
                                        $row .= '<input type="hidden" value="' . $id . '" name="ReservationDetail[room_id][]" />';
                                        $row .= 'No : ' . $detail->Room->number . '<br>';
                                        $row .= 'Type : ' . $detail->Room->RoomType->name . ' - ' . ucfirst(strtolower($detail->Room->bed)) . '<br>';
                                        $row .= 'Floor : ' . $detail->Room->floor . '<br>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<textarea disabled style="width:150px" rows="3" name="ReservationDetail[guest_user_names][]">' . $detail->guest_user_names . '</textarea>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<input disabled style="width:30px" onChange="calculation()" class="pax" type="text" value="' . $detail->pax . '" name="ReservationDetail[pax][]" />';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= "<input type='hidden' name='ori_fnb_price' class='ori_fnb_price' value='" . $detail->Room->RoomType->fnb_charge . "' />";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input disabled style="width:70px" onChange="calculation()" readOnly class="edit_price fnb_price" type="text" value="' . $detail->fnb_price . '" name="ReservationDetail[fnb_price][]" /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<input disabled style="width:30px" onChange="calculation()" type="text" class="extrabed" value="' . $detail->extrabed . '" name="ReservationDetail[extrabed][]" />';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input disabled style="width:70px" onChange="calculation()" readOnly class="edit_price extrabed_price" type="text" value="' . $detail->extrabed_price . '" name="ReservationDetail[extrabed_price][]" /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= $checkbox_others_include;
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= "<input type='hidden' name='ori_rate' class='ori_rate' value='" . $rate[$usertype]['default'] . "' />";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" class="room_rate edit_price" readOnly  type="text" value="' . $detail->room_price . '" name="ReservationDetail[room_price][]" /></div>';
                                        $row .= "</td>";
                                        $row .= "<td>";
                                        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" type="text" class="total_rate" value="' . $detail->charge . '" name="ReservationDetail[charge][]" readonly /></div>';
                                        $row .= "</td>";
                                        $row .= "</tr>";
                                        $row .= '<tr id="selected" style="display:none"></tr>';
                                        echo $row;
                                    }
                                }

                                if (!empty($_GET['number']) && !empty($_GET['date'])) {
                                    $thisRoom = Room::model()->findByPk($_GET['number']);
                                    if (!empty($thisRoom)) {
                                        $rate = json_decode($thisRoom->RoomType->rate, true);
                                        $thisRoles = Roles::model()->findAll(array('index' => 'id'));
                                        $price = '';
                                        foreach ($rate as $o => $value) {
                                            $price .= $thisRoles[$o]->name . '<br>';
                                            $price .= '- &nbsp;&nbsp;' . landa()->rp($value['min']) . '<br>';
                                            $price .= '- &nbsp;&nbsp;' . landa()->rp($value['default']) . '<br>';
                                            $price .= '- &nbsp;&nbsp;' . landa()->rp($value['max']) . '<br>';
                                            $price .= '<input type="hidden" id="price' . $thisRoles[$o]->id . '" value="' . $value['default'] . '" />';
                                        }
                                        ?>
                                        <tr>
                                    <input id="<?php echo $thisRoom->id ?>" name="RegistrationDetail[room_id][]" value="<?php echo $thisRoom->id ?>" type="hidden">
                                    <td class="span1" style="text-align:center"><?php echo $thisRoom->number; ?></td>
                                    <td class="span3"><?php echo $thisRoom->RoomType->name; ?></td>
                                    <td class="span1" style="text-align:center"><?php echo $thisRoom->floor; ?></td>
                                    <td class="span2"><?php echo $thisRoom->bed ?></td>
                                    <td class="span3"><?php echo $price; ?></td>
                                    <td><div class="input-prepend"><span class="add-on">Rp</span><input style="width:100px" name="RegistrationDetail[price][]" id="price_roles" type="text" value="" ></div></td>                                
                                    <td style="width:30px"><a class="btn btn-small removeSelect" title="" rel="tooltip" data-original-title="Remove"><i class="cut-icon-minus-2"></i></a><div class="tooltip fade top in" style="top: 25px; left: 640px; display: none;"><div class="tooltip-arrow"></div><div class="tooltip-inner">Add</div></div></td>
                                    </tr> 
                                    <?php
                                }
                            }
                            ?>                            
                            <tr id="selected" style="display:none">
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
                    <?php echo $form->dropdownListRow($model, 'reserved_by', array('' => 'Please Choose', 'Telephone' => 'Telephone', 'Fax' => 'Fax', 'EMail' => 'EMail', 'Website' => 'Website', 'Walk In' => 'Walk In'), array('disabled' => true)); ?>

                    <?php echo $form->textFieldRow($model, 'cp_name', array('disabled' => true, 'style' => 'width:100%', 'maxlength' => 45)); ?>

                    <?php echo $form->textFieldRow($model, 'cp_telephone_number', array('disabled' => true, 'style' => 'width:100%', 'maxlength' => 45)); ?>

                    <?php echo $form->textAreaRow($model, 'cp_note', array('disabled' => true, 'style' => 'width:100%;height:50px')); ?>
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
                        echo $form->select2Row($model, 'billing_user_id', array(
                            'asDropDownList' => true,
                            'data' => $data,
                            'disabled' => true,
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => true,
                                'width' => '100%',
                            ),
                                )
                        );
                        ?>
                        <?php echo $form->textAreaRow($model, 'billing_note', array('disabled' => true, 'style' => 'width:100%;height:50px')); ?>                        

                    </div>

                </div>

            </div>
        </div>
        <div class="tab-pane" id="remarks">
            <div class="well" style="height: 100px">
                <?php echo $model->remarks; ?>
            </div>
        </div>
        <div class="tab-pane" id="dp">
            <?php
            $dp_by = array('cash' => 'Cash', 'cc' => 'Credit Card', 'debit' => 'Debit Card');
            echo $form->textFieldRow($modelDp, 'code', array('class' => 'span3', 'disabled' => true));
            echo $form->dropDownListRow($modelDp, 'dp_by', $dp_by, array('class' => 'span2', 'maxlength' => 5, 'empty' => 'Please Choose'));
            echo $form->textFieldRow($modelDp, 'amount', array('class' => 'span3', 'prepend' => 'Rp', 'disabled' => true));
            echo $form->textFieldRow($modelDp, 'cc_number', array('class' => 'span3', 'disabled' => true));
            echo $form->textAreaRow($modelDp, 'description', array('rows' => 6, 'cols' => 50, 'class' => 'span8', 'disabled' => true));
            ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
<script>
    $("#myTab a").click(function(e) {
        e.preventDefault();
        $(this).tab("show");
    });
</script>
