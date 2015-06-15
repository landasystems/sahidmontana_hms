<?php
$this->setPageTitle('Change Rate');
$this->breadcrumbs = array(
    'Change Rate' => array('roomBill/editPaxExtrabed'),
    'Update',
);
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'edit-charge-form',
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
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert fade in alert-' . $key . '">' . $message . '</div>';
}
?>

<div class="box  invoice">
    <div class="title clearfix">
        <h4 class="left">
            <span class="blue cut-icon-bookmark"></span>
            <span>Change Rate</span>                        
        </h4>
        <div class="invoice-info">
            <span class="number"> <strong class="red">
                </strong></span>
        </div> 
    </div>

    <div class="content">   
        <table style="width:100%">
            <tr><td style="vertical-align: top">

                    <div class="control-group ">
                        <label class="control-label">Find Room :</label>
                        <div class="controls">
                            <?php
                            if (!empty($_GET['number'])) {
                                $id = $_GET['number'];
                                $newModel = Room::model()->findByPk($id);
                                $number = $newModel->number;
                                $guest_id = $newModel->Registration->guest_user_id;
                                $guest_name = $newModel->Registration->Guest->name;
                                $checkIn = $newModel->Registration->date_from;
                                $regCode = $newModel->Registration->code;

                                $roomBill = RoomBill::model()->find(array('order' => 'date_bill desc', 'condition' => 'room_id=' . $id . ' and is_checkedout=0'));
                                $checkOut = date("Y-m-d", strtotime($roomBill->date_bill));
                            }

                            $data = array(0 => t('choose', 'global')) + CHtml::listData($room, 'room_id', 'Room.fullRoom');
                            $this->widget(
                                    'bootstrap.widgets.TbSelect2', array(
                                'asDropDownList' => true,
                                'name' => 'roomId',
                                'data' => $data,
                                'value' => (!empty($number)) ? $number : '',
                                'htmlOptions' => array(
                                    'multiple' => 'multiple',
                                ),
                                'options' => array(
                                    "placeholder" => t('choose', 'global'),
                                    "allowClear" => true,
                                    'width' => '80%',
                                ),
                                'events' => array('change' => 'js: function() {
                                    $(".items").remove();
                                    $.ajax({
                                       url : "' . url('roomBill/detChangeCharge') . '",
                                       type : "POST",
                                       data :  { roomID:  $(this).val()},
                                       success : function(data){ 
                                           $("#addRow").replaceWith(data);
                                           calculation();
                                       }
                                    });
                       }'),
                                    )
                            );
                            ?>
                        </div>
                    </div>
                    <hr style="margin:0px;padding: 0px">
                    <input id="type" type="hidden" value="" name="type" />
                </td>                
            </tr>
        </table>

        <?php
        $siteConfig = SiteConfig::model()->findByPk(1);
        $settings = json_decode($siteConfig->settings, true);
        $fnb = (!empty($settings['fb_charge'])) ? $settings['fb_charge'] : 0;
        $exbed = (!empty($settings['extrabed_charge'])) ? $settings['extrabed_charge'] : 0;
        ?>
        <div class="row-fluid">
            <div class="span6">Package : 
                <?php
                $roomTypePackage = array(0 => t('choose', 'global')) + CHtml::listData(RoomType::model()->findAll(array('condition' => 'is_package=1')), 'id', 'name');
                echo Chtml::dropdownList('Registration[package_room_type_id]', '', $roomTypePackage, array('class' => 'span9',));
                ?><br/><br/>
                <div class="detail_paket" ></div>
            </div>
            <div class="span6" style="text-align:right">
                <a class="btn btn-small btn-primary" data-toggle="modal" data-target="#modalTools"><i class="icon-edit icon-white" style="margin:0px !important"></i> Tools</a>
            </div>
        </div>
        <div class="clearfix"></div>

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
                                            <input  class="angka editOtherInclude price-' . $tuyul->id . ' edit_price" kode="' . $tuyul->id . '" name="_' . $tuyul->id . '" type="text" value="' . $charge . '">
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
                    <input class="edit_price angka" type="text" value="0" style="width: 20px" id="txt_pax" maxlength="1">
                    <a style="margin-left: 10px" class="btn btn-small edit_price" id="btn_pax">Apply All</a>
                </div>
            </div>
            <div class="control-group ">
                <label class="control-label" for="">FB Price</label>                                                            
                <div class="controls">
                    <div class="input-prepend ">
                        <span class="add-on">Rp</span>
                        <input class="edit_price angka" type="text" value="0" id="txt_fb_price">
                        <a style="margin-left: 10px" class="btn btn-small edit_price" id="btn_fb_price">Apply All</a>
                    </div>                           
                </div>
            </div>
            <div class="control-group ">
                <label class="control-label" for="">EB Price</label>                                                            
                <div class="controls">
                    <div class="input-prepend ">
                        <span class="add-on">Rp</span>
                        <input class="edit_price angka" type="text" value="0" id="txt_eb_price">
                        <a style="margin-left: 10px" class="btn btn-small edit_price" id="btn_eb_price">Apply All</a>
                    </div>                           
                </div>
            </div>
            <div class="control-group ">
                <label class="control-label" for="">Room Charge</label>                                                            
                <div class="controls">
                    <div class="input-prepend ">
                        <span class="add-on">Rp</span>
                        <input class="edit_price angka" type="text" value="0" id="txt_room_rate">
                        <a style="margin-left: 10px" class="btn btn-small edit_price" id="btn_room_rate">Apply All</a>
                    </div>                           
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>

        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th class="span3">Room</th>
                    <th>Pax</th>
                    <th>F&B Price</th>
                    <th>EB</th>
                    <th>EB Price</th>
                    <th><input type="checkbox" id="all_others" class="all_others" style="margin:0px;padding:0px"> Other Include</th>
                    <th>Room Charge</th>                                
                    <th>Room Rate</th>                                
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
                    <td></td>
                    <td></td>
                </tr> 
            </tbody>
        </table>   


        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'label' => 'Change Rate',
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
    </div>

</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
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
            var price_default = (fnb * pax) + (bed * bed_price) + room_rate + other;
            $(this).parent().parent().find(".total_rate").val(price_default);
        });
    }

    $("#all_others").click(function (event) {  //on click 
        if (this.checked) { // check select status
            $(".others_include").prop("checked", true);
        } else {
            $(".others_include").prop("checked", false);
        }
        calculation();
    });

    $("#btn_eb_price").on("click", function () {
        var disabled = $(this).attr("disabled") || 0;
        if (disabled == 0) {
            var nilai = $("#txt_eb_price").val();
            $(".extrabed_price").val(nilai);
            calculation();
        }
    });
    $("#btn_pax").on("click", function () {
        var disabled = $(this).attr("disabled") || 0;
        if (disabled == 0) {
            var nilai = $("#txt_pax").val();
            $(".pax").val(nilai);
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
    $("#Registration_package_room_type_id").on("change", function () {
        if ($(this).val() == 0) {
            $(".detail_paket").html('');
            $(".pckg").html('');
            calculation();
        } else {
            $.ajax({
                url: "<?php echo url('registration/getPackage'); ?>",
                type: "POST",
                data: $('form').serialize(),
                success: function (data) {
                    $(".detail_paket").html(data);
                    data = $('#detPackage').val();
                    data = JSON.parse(data);

                    $(".pckg").each(function () {
                        a = this;
                        result = '';
                        $.each(data, function (i, n) {
                            room_id = $(a).parent().parent().find('.room_id').val();
                            result += '<label><input checked class="others_include ' + n['id'] + '" kode="' + n['id'] + '" style="margin:0px 5px 0px 0px" type="checkbox" r="' + room_id + '" name="others_include[' + room_id + '][' + n['id'] + ']"  value="' + n['total'] + '">' + n['name'] + '</label>';
                        });
                        $(a).html(result);
                    });
                    calculation();
                }
            });
        }
    });

    $("#edit-charge-form").on('click', '.others_include', function (event) {  //on click    
        calculation();
    });
</script>