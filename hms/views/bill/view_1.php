

<?php
$this->setPageTitle('View Bills | ID : ' . $model->id);
$this->breadcrumbs = array(
    'Bills' => array('index'),
    $model->id,
);
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
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv("printableArea");return false;')),
        array('label' => 'Export Excel', 'icon' => 'entypo-icon-export', 'url' => Yii::app()->controller->createUrl('GenerateReport', array('id' => $model->id)), 'linkOptions' => array()),
)));
?>
<?php
$this->endWidget();
$det = '<table class="tbPrint">';
$det .= '<tr><td id="bill_print"  style="text-align:center"><b>Date</b></td><td id="bill_print"  style="text-align:center"><b>Description</b></td><td  id="bill_print" style="text-align:center"><b>Room</b></td><td  id="bill_print" style="text-align:center"><b>Amount</b></td><td  id="bill_print"  style="text-align:center"><b>Credit</b></td><td  id="bill_print" style="text-align:center"><b>Charge</b></td><td  id="bill_print" style="text-align:center"><b>Subtotal</b></td><tr>';
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
                <td style="vertical-align: top"><?php // echo $model->Guest->name;                                     ?></span></td>                                
            </tr>-->
            <tr>
                <td class="span2" style="vertical-align: top">Mr. / Mrs.</td>
                <td style="width:5px;vertical-align: top">:</td>
                <td style="vertical-align: top"><?php echo nl2br(strtoupper($model->pax_name)); ?></span></td>                                
            </tr>
            <tr>
                <td style="vertical-align: top">Date Check In</td>
                <td>:</td>
                <td style="vertical-align: top"><?php echo date("l Y-m-d H:i:s", strtotime($model->arrival_time)); ?></td>                                
            </tr>      
            <tr>
                <td>Date Check Out</td>
                <td>:</td>
                <td><?php echo date("l Y-m-d H:i:s", strtotime($model->departure_time)); ?></td>                                                                                         
            </tr>
            <tr>
                <td style="vertical-align: top">Remarks</td>
                <td style="vertical-align: top">:</td>
                <td style="vertical-align: top">
                    <?php echo CHtml::textArea('description', $model->description, array('disabled' => true, 'style' => 'width:100%')); ?>
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


                <?php
                $siteConfig = SiteConfig::model()->findByPk(1);
                $settings = json_decode($siteConfig->settings, true);
                $fnb = (!empty($settings['fb_charge'])) ? $settings['fb_charge'] : 0;
                $exbed = (!empty($settings['extrabed_charge'])) ? $settings['extrabed_charge'] : 0;
                $return = '';
                $batas = 0;
                $tambahan_detail = '';
                $tambahan_detail2 = '';
                foreach ($details as $detail) {
                    if (empty($detail->room_bill_id)) {
                        $deposit = Deposite::model()->findByPk($detail->deposite_id);
                        $return .= '<tr class="items">
                                        <td style="text-align:center"><i class="minia-icon-arrow-right-2"></td>
                                        <td>' . 'Deposit Guest [' . $deposit->code . ']</td>
                                        <td style="text-align:center">-</td>
                                        <td>' . date("l Y-m-d H:i:s", strtotime($deposit->created)) . '</td>                                                        
                                        <td style="text-align:center">' . '-' . '</td>                                                        
                                        <td style="text-align:right">' . landa()->rp($detail->deposite_amount) . '</td>                                                        
                                        <td style="text-align:right">' . landa()->rp($detail->deposite_amount * -1) . '</td>                                                        
                                    </tr>';
                        $det .= '<tr>'
                                . '<td  id="bill_print" style="text-align:center">' . date("d-M-y", strtotime($deposit->created)) . '</td>'
                                . '<td  id="bill_print" style="text-align:left"><b>' . 'Deposit Guest [' . $deposit->code . ']</b></td>'
                                . '<td  id="bill_print" style="text-align:center">-</td>'
                                . '<td  id="bill_print" style="text-align:center">-</td>'
                                . '<td  id="bill_print" style="text-align:right">' . landa()->rp($detail->deposite_amount) . '</td>'
                                . '<td  id="bill_print" style="text-align:right">-</td>'
                                . '<td  id="bill_print" style="text-align:right">' . landa()->rp($detail->deposite_amount * -1) . '</td>'
                                . '<tr>';
                        $batas++;
                    } else {
                        $roomBill = RoomBill::model()->findByPk($detail->room_bill_id);
                        $room_price = $roomBill->room_price;
                        if ($roomBill->charge == 0) {
                            $extrapax = 0;
                            $charge = 0;
                            $extrabed = 0;
                            $room_price = 0;
                        } else {
                            $extrapax = $roomBill->pax * $roomBill->fnb_price;
                            $extrabed = $roomBill->extrabed * $roomBill->extrabed_price;
                        }
                        $txtExtrabed = ($roomBill->extrabed == 0) ? '' : ' + Extrabed';
                        $room_price = $room_price + $extrabed + $extrapax;

                        //other include
                        if ($roomBill->others_include != '') {
                            $others_include = json_decode($roomBill->others_include);
                            foreach ($others_include as $key => $mInclude) {
                                $tuyul = ChargeAdditional::model()->findByPk($key);
                                $txtExtrabed .= ' + ' . $tuyul->name;
                                $room_price += $mInclude;
                            }
                        }

                        $return.= '<tr class="items">
                        <td style="text-align:center"><i class="minia-icon-arrow-right-2"></td>
                        <td>Room Rate' . $txtExtrabed . '</td>
                        <td style="text-align:center">' . $roomBill->room_number . '</td>
                        <td>' . date("l, d-M-Y", strtotime($roomBill->date_bill)) . '</td>                                                        
                        <td style="text-align:center">1</td>                        
                        <td style="text-align:right">' . landa()->rp($room_price) . '</td>                                                        
                        <td style="text-align:right">' . landa()->rp($room_price) . '</td>                                                        
                        </tr>';

                        $det .= '<tr>'
                                . '<td  id="bill_print" style="text-align:center">' . date("d-M-y", strtotime($roomBill->date_bill)) . '</td>'
                                . '<td  id="bill_print" style="text-align:left">Room Rate' . $txtExtrabed . '</td>'
                                . '<td  id="bill_print" style="text-align:center">' . $roomBill->room_number . '</td>'
                                . '<td  id="bill_print" style="text-align:center">1</td>'
                                . '<td  id="bill_print" style="text-align:right"></td>'
                                . '<td  id="bill_print" style="text-align:right">' . landa()->rp($room_price) . '</td>'
                                . '<td  id="bill_print" style="text-align:right">' . landa()->rp($room_price) . '</td>'
                                . '<tr>';
                        $batas++;


                        //package 
                        if (!empty($roomBill->package_room_type_id)) {
                            $package = RoomType::model()->findByPk($roomBill->package_room_type_id);
                            $listPackage = json_decode($package->charge_additional_ids);
                            foreach ($listPackage as $pg) {
                                $tuyul = ChargeAdditional::model()->findByPk($pg->id);
                                $return .= '<tr class="items">
                                            <td style="text-align:center"></td>
                                            <td> &nbsp;&nbsp;&raquo;  ' . $tuyul->name . '</td>
                                            <td style="text-align:center">' . $roomBill->room_number . '</td>
                                            <td>' . date("l Y-m-d H:i:s", strtotime($roomBill->date_bill)) . '</td>                                                        
                                            <td style="text-align:center">' . $pg->amount . '</td>                                                        
                                            <td style="text-align:right">' . landa()->rp($pg->charge) . '</td>                                                        
                                            <td style="text-align:right">' . landa()->rp($pg->amount * $pg->charge) . '</td>                                                        
                                        </tr>';

                                $det .= '<tr>'
                                        . '<td  id="bill_print" style="text-align:center">' . date("d-M-y", strtotime($roomBill->date_bill)) . '</td>'
                                        . '<td  id="bill_print"> &nbsp;&nbsp;&raquo;&nbsp;  ' . $tuyul->name . '</td>'
                                        . '<td  id="bill_print" style="text-align:center">' . $roomBill->room_number . '</td>'
                                        . '<td  id="bill_print" style="text-align:center">' . $pg->amount . '</td>'
                                        . '<td  id="bill_print" style="text-align:right"></td>'
                                        . '<td  id="bill_print" style="text-align:right">' . landa()->rp($pg->charge) . '</td>'
                                        . '<td  id="bill_print" style="text-align:right">' . landa()->rp($pg->amount * $pg->charge) . '</td>'
                                        . '<tr>';
                                $batas++;
                            }
                        }


                        $additionBills = BillCharge::model()->findAll(array('condition' => 'gl_room_bill_id=' . $detail->room_bill_id_leader));
                        $departement = ChargeAdditionalCategory::model()->findAll();
                        $data = CHtml::listData($departement, 'id', 'name');
                        foreach ($additionBills as $additionBill) {
                            $cekRoomBill = RoomBill::model()->findAll(array('condition' => 'date_bill ="' . date('Y-m-d', strtotime($additionBill->created)) . '" and registration_id=' . $roomBill->registration_id . ' and room_id=' . $roomBill->room_id));
                            if (empty($cekRoomBill) && $additionBill->gl_room_bill_id == $roomBill->id) {
                                $detAdd = BillChargeDet::model()->findByAttributes(array('bill_charge_id' => $additionBill->id));
                                $tambahan_detail .= '<tr class="items">
                                                        <td style="text-align:center"></td>
                                                        <td> &nbsp;&nbsp;&raquo; ' . $data[$detAdd->Additional->ChargeAdditionalCategory->root] . ' [' . $additionBill->code . ']' . '</td>
                                                        <td style="text-align:center">' . $additionBill->RoomBill->room_number . '</td>
                                                        <td>; ' . date("l Y-M-d H:i:s", strtotime($additionBill->created)) . '</td>                                                        
                                                        <td style="text-align:center">' . '-' . '</td>                                                        
                                                        <td style="text-align:right">' . landa()->rp($additionBill->gl_charge) . '</td>                                                        
                                                        <td style="text-align:right">' . landa()->rp($additionBill->gl_charge) . '</td>                                                        
                                                    </tr>';

                                $tambahan_detail2 .= '<tr>'
                                        . '<td  id="bill_print"  style="text-align:center">' . date("d-M-y", strtotime($additionBill->created)) . '</td>'
                                        . '<td  id="bill_print"  style="text-align:left"> &nbsp;&nbsp;&raquo;&nbsp;' . $data[$detAdd->Additional->ChargeAdditionalCategory->root] . ' [' . $additionBill->code . ']' . '</td>'
                                        . '<td id="bill_print"  style="text-align:center">' . $additionBill->RoomBill->room_number . '</td>'
                                        . '<td id="bill_print"  style="text-align:center">-</td>'
                                        . '<td  id="bill_print" style="text-align:right"></td>'
                                        . '<td  id="bill_print" style="text-align:right">' . landa()->rp($additionBill->gl_charge) . '</td>'
                                        . '<td id="bill_print"  style="text-align:right">' . landa()->rp($additionBill->gl_charge) . '</td>'
                                        . '<tr>';
                                $batas++;
                            }
                            if (date('Y-m-d', strtotime($additionBill->created)) == $roomBill->date_bill) {
                                $detAdd = BillChargeDet::model()->findByAttributes(array('bill_charge_id' => $additionBill->id));
                                $return .= '<tr class="items">
                                                        <td style="text-align:center"></td>
                                                        <td> &nbsp;&nbsp;&raquo; ' . $data[$detAdd->Additional->ChargeAdditionalCategory->root] . ' [' . $additionBill->code . ']' . '</td>
                                                        <td style="text-align:center">' . $additionBill->RoomBill->room_number . '</td>
                                                        <td>' . date("l Y-M-d H:i:s", strtotime($additionBill->created)) . '</td>                                                        
                                                        <td style="text-align:center">' . '-' . '</td>                                                        
                                                        <td style="text-align:right">' . landa()->rp($additionBill->gl_charge) . '</td>                                                        
                                                        <td style="text-align:right">' . landa()->rp($additionBill->gl_charge) . '</td>                                                        
                                                    </tr>';

                                $det .= '<tr>'
                                        . '<td  id="bill_print"  style="text-align:center">' . date("d-M-y", strtotime($additionBill->created)) . '</td>'
                                        . '<td  id="bill_print"  style="text-align:left"> &nbsp;&nbsp;&raquo;&nbsp;' . $data[$detAdd->Additional->ChargeAdditionalCategory->root] . ' [' . $additionBill->code . ']' . '</td>'
                                        . '<td id="bill_print"  style="text-align:center">' . $additionBill->RoomBill->room_number . '</td>'
                                        . '<td id="bill_print"  style="text-align:center">-</td>'
                                        . '<td  id="bill_print" style="text-align:right"></td>'
                                        . '<td  id="bill_print" style="text-align:right">' . landa()->rp($additionBill->gl_charge) . '</td>'
                                        . '<td id="bill_print"  style="text-align:right">' . landa()->rp($additionBill->gl_charge) . '</td>'
                                        . '<tr>';
                                $batas++;
                            }
                        }
                    }
                }

                $return .= $tambahan_detail;
                $det .= $tambahan_detail2;
                $max_row = 2;
                if ($batas <= $max_row) {
                    for ($batas; $batas <= $max_row; $batas++) {
                        $return .= '<tr class="items">
                                                        <td style="text-align:center"></td>
                                                        <td> &nbsp;</td>
                                                        <td style="text-align:center">&nbsp;</td>
                                                        <td> &nbsp;</td>                                                        
                                                        <td style="text-align:center">&nbsp;</td>                                                        
                                                        <td style="text-align:right">&nbsp;</td>                                                        
                                                        <td style="text-align:right">&nbsp;</td>                                                        
                                                    </tr>';

                        $det .= '<tr>'
                                . '<td  id="bill_print"  style="text-align:center">&nbsp;</td>'
                                . '<td  id="bill_print"  style="text-align:left">&nbsp;</td>'
                                . '<td id="bill_print"  style="text-align:center">&nbsp;</td>'
                                . '<td id="bill_print"  style="text-align:center">&nbsp;</td>'
                                . '<td  id="bill_print" style="text-align:right">&nbsp;</td>'
                                . '<td  id="bill_print" style="text-align:right">&nbsp;</td>'
                                . '<td id="bill_print"  style="text-align:right">&nbsp;</td>'
                                . '<tr>';
                    }
                }
                echo $return;
                ?>



                <tr>
                    <td colspan="6" style="text-align: right">
                        <b>Grand Total :</b>                             
                    </td>
                    <td style="text-align:right"><?php echo landa()->rp($model->total); ?></td>                                                        
                </tr>



                <tr>                        
                    <td colspan="6" style="text-align: right">
                        <b>Cash :</b>                
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input disabled="true" style="width:100px" id="cash" name="cash" value="<?php echo $model->cash; ?>"  type="text">
                        </div>                        
                    </td>                                                        
                </tr>                
                <tr >
                    <td colspan="6" style="text-align: right">
                        <b>Credit Card :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input disabled="true" style="width:100px" id="credit" name="credit"  type="text" value="<?php echo $model->cc_charge; ?>">
                        </div>
                    </td>                                                        
                </tr>
                <tr>
                    <td colspan="6" style="text-align: right">
                        <b>Credit Card Number :</b>                        
                    </td>
                    <td style="text-align:right">
                        <input disabled="true" style="width:90%" id="cc_number" name="cc_number"  type="text" value="<?php echo $model->cc_number; ?>">
                    </td>                                                        
                </tr>
                <tr >
                    <td colspan="6" style="text-align: right">
                        <b>City Ledger :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input disabled="true" style="width:100px" id="cl" name="cl"  type="text" value="<?php echo $model->ca_charge; ?>">
                        </div>
                    </td>                                                        
                </tr>
                <tr>
                    <td colspan="6" style="text-align: right">
                        <b>City Ledger Name:</b>                        
                    </td>
                    <td style="text-align:right">
                        <?php
                        $billTo = (!empty($model->ca_user_id)) ? ucwords($model->BillTo->name) : '';
                        ?>
                        <input disabled="true" style="width:90%" id="cl" name="cl"  type="text" value="<?php echo $billTo; ?>">
                    </td>                                                        
                </tr>   
                <tr >
                    <td colspan="6" style="text-align: right">
                        <b>Discount :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend">
                            <span class="add-on">Rp</span>
                            <input class="angka" style="width:100px" disabled="true" value="<?php echo $model->discount; ?>" id="discount" name="discount"  type="text">                            
                        </div>
                    </td>                                                        
                </tr>
                <tr>                    
                    <td colspan="6" style="text-align: right">
                        <b>Refund</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input style="width:100px" id="refund" name="refund" value="<?php echo $model->refund; ?>" ReadOnly type="text">                            
                        </div>
                    </td>                                                        
                </tr>

            </tbody>
        </table>  
    </div>
</div> 
<?php $this->endWidget(); ?>


<div id='printableArea' style="display: none"> 
    <style type="text/css">
        /*.printableArea{display: none}*/ 
        /*    @media print
            {*/
        #bill_print{
            margin: 3px;
            padding:3px;
            line-height: 12px;
            font-size: 10px;        
            /*font-family: monospace;*/
            page-break-after: avoid;
        }
        #print{        
            font-size: 10px;        
            /*font-family: monospace;*/        
        }
        /*        #wrapper {display: none}
                .printableArea{display: block !important;width: 100%;top: 0px;left: 0px;position: absolute;}*/

        /*}*/
    </style>
    <?php
    $this->renderPartial('_excelBill', array('model' => $model, 'details' => $details));
    ?>
</div>
<?php // echo $content; ?>

<script type="text/javascript">
    function printDiv(divName)
    {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();

        document.body.innerHTML = originalContents;
        $("#myTab a").click(function(e) {
            e.preventDefault();
            $(this).tab("show");
        });
    }
</script>