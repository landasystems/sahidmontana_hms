<style>
    th {
        vertical-align: middle !important;
        text-align: center !important;
    }
</style>
<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'bill-cashier-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>

    <div id="printableArea">
        <legend style="text-align: center">
            <h3 style="padding: 0px">CASHIER SHEET</h3>
            <p class="note" style="font-size: 15px"><?php echo date("l d-F-Y"); ?></p>
        </legend>

        <table class="table ">
            <thead>
                <tr>
                    <th class="span1" rowspan="2" style="vertical-align:middle;text-align: center;">No.</th>
                    <th class="span2" rowspan="2" style="vertical-align:middle;text-align: center;">Type</th>
                    <th class="span2" rowspan="2" style="vertical-align:middle;text-align: center;">Code Transaction</th>
                    <th class="span2" rowspan="2" style="vertical-align:middle;text-align: center;">Room</th>
                    <th class="span2" colspan="2" style="vertical-align:middle;text-align: center;">Cash</th>
                    <th class="span2" rowspan="2" style="vertical-align:middle;text-align: center;">G. Ledger</th>
                    <th class="span2" rowspan="2" style="vertical-align:middle;text-align: center;">C. Card</th>
                    <th class="span2" rowspan="2" style="vertical-align:middle;text-align: center;">C/A</th>                 
                    <th class="span2" rowspan="3" style="vertical-align:middle;text-align: center;">Remarks</th>                                                        
                </tr>
                <tr >
                    <th class="span2" style="vertical-align:middle;text-align: center;">Tunai</th>
                    <th class="span2" style="vertical-align:middle;text-align: center;">Refund DP</th>                                                    
                </tr>                        

            </thead>
            <tbody>
                <?php
                //************** guest billing *******************//
                $bills = Bill::model()->findAll(array('condition' => 'created_user_id=' . Yii::app()->user->id . ' and is_cashier=0'));
                $tot_cash = 0;
                $tot_refund = 0;
                $tot_credit = 0;
                $tot_ca = 0;
                $tot_gl = 0;
                $no = 1;
                $room = array();
                foreach ($bills as $bill) {
                    $billdate = BillDet::model()->findAll(array('with' => 'RoomBill', 'condition' => 'RoomBill.lead_room_bill_id=0 AND bill_id=' . $bill->id));
                    $room_number = array();
                    foreach ($billdate as $m) {
                        $room_number[] = $m->RoomBill->room_number;
                    }
                    $room = (empty($room_number)) ? '-' : implode(', ', $room_number);

                    $cash = ($bill->total > 0) ? $bill->cash - $bill->refund : $bill->cash;
                    $refund = ($bill->total < 0 && $bill->refund > 0) ? $bill->refund : 0;
                    $credit = $bill->cc_charge;
                    $ca = $bill->ca_charge;

                    $tot_cash += $cash;
                    $tot_refund += ($bill->total < 0 && $bill->refund > 0) ? $refund : 0;
                    $tot_credit += $credit;
                    $tot_ca += $ca;

                    $row = '<tr>';
                    $row .= '<td style="text-align:center">' . $no . '</td>';
                    $row .= '<td style="text-align:left">' . 'Guest Checkedout' . '</td>';
                    $row .= '<td style="text-align:left">' . $bill->code . '</td>';
                    $row .= '<td style="text-align:right">' . $room . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($cash) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($refund) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($credit) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($ca) . '</td>';
                    $row .= '<td style="text-align:left">' . $bill->description . '</td>';
                    $row .= '</tr>';
                    echo $row;
                    $no++;
                }
                ?>


                <?php
                //************** guest deposite *******************//
                $deposit = Deposite::model()->findAll(array('condition' => 'created_user_id=' . Yii::app()->user->id . ' and is_cashier=0'));
                foreach ($deposit as $dp) {
                    $room = '';
                    $room_number = array();
                    $is_regis = Registration::model()->find(array('condition' => 'deposite_id=' . $dp->id));
                    if (isset($is_regis)) {
                        $roomDet = RegistrationDetail::model()->findAll(array('condition' => 'registration_id=' . $is_regis->id));
                        foreach ($roomDet as $roomNumber) {
                            $room_number[] = $roomNumber->room_id;
                        }
                        $room = (empty($room_number)) ? '-' : implode(', ', $room_number);
                    } else {
                        $room = '-';
                    }

                    if ($dp->dp_by == 'cash') {
                        $cash = $dp->amount;
                        $refund = 0;
                        $credit = 0;
                        $ca = 0;
                    } else {
                        $cash = 0;
                        $refund = 0;
                        $credit = $dp->amount;
                        $ca = 0;
                    }


                    $tot_cash += $cash;
                    $tot_refund += 0;
                    $tot_credit += $credit;
                    $tot_ca += $ca;
                    $tipe = "Guest Deposite";
//                $tipe = ($dp->is_walk_in_cash == 1) ? "Walk In Cash" : "Guest Deposite";

                    $row = '<tr>';
                    $row .= '<td style="text-align:center">' . $no . '</td>';
                    $row .= '<td style="text-align:left">' . $tipe . '</td>';
                    $row .= '<td style="text-align:left">' . $dp->code . '</td>';
                    $row .= '<td style="text-align:left">' . $room . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($cash) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($refund) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($credit) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($ca) . '</td>';
                    $row .= '<td style="text-align:left">' . $dp->description . '</td>';
                    $row .= '</tr>';
                    echo $row;
                    $no++;
                }
                ?>


                <?php
                //************** guest transaction *******************//
                $category = chtml::listData(ChargeAdditionalCategory::model()->findAll(), 'id', 'name');
                $transaction = BillCharge::model()->findAll(array('condition' => 'created_user_id=' . Yii::app()->user->id . ' and is_cashier=0 AND is_temp=0'));
                $roombill;
                foreach ($transaction as $trans) {
//                    $roombill = RoomBill::model()->findAll(array('condition' => 'id='.$trans->gl_room_bill_id));
                    $room = (isset($trans->RoomBill->room_number)) ? $trans->RoomBill->room_number : '-';
                    $cash = ($trans->total > 0) ? $trans->cash - $trans->refund : $trans->cash;
                    $refund = 0;
                    $credit = $trans->cc_charge;
                    $ca = $trans->ca_charge;
                    $gl = $trans->gl_charge;
                    $glroom = $trans->gl_room_bill_id;
                    $type = ChargeAdditionalCategory::model()->findByPk($trans->ChargeAdditionalCategory->root);


                    $tot_cash += $cash;
                    $tot_refund += 0;
                    $tot_credit += $credit;
                    $tot_ca += $ca;
                    $tot_gl += $gl;

                    $row = '<tr>';
                    $row .= '<td style="text-align:center">' . $no . '</td>';
                    $row .= '<td style="text-align:left">' . $type->name . '</td>';
                    $row .= '<td style="text-align:left">' . $trans->code . '</td>';
                    $row .= '<td style="text-align:right">' . $room . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($cash) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($refund) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($gl) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($credit) . '</td>';
                    $row .= '<td style="text-align:right">' . landa()->rp($ca) . '</td>';
                    $row .= '<td style="text-align:left">' . $trans->description . '</td>';
                    $row .= '</tr>';
                    echo $row;
                    $no++;
                }
                ?>



                <tr style="height:40px !important;">
                    <th style="text-align: left !important" colspan="4"><b>TOTAL</b></th>                            
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_cash); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_refund); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_gl); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_credit); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_ca); ?></th>
                    <th style="text-align: right !important"></th>                            
                </tr>

            </tbody>
        </table>  
        <table>
            <tr>
                <td class="span6">Prepared By : <?php echo Yii::app()->user->name; ?></td>
                <td class="span6">Approved By : <?php echo ''; ?></td>                        
            </tr>
        </table>

    </div>




    <div class="form-actions">
        <input type="hidden" name="is_post" value="1" />
        <?php
//        $this->widget('bootstrap.widgets.TbButton', array(
//            'buttonType' => 'submit',
//            'type' => 'primary',
//            'icon' => 'ok white',
//            'label' => $model->isNewRecord ? 'Create' : 'Save',
//        ));
//        
        ?>
    </div>

    <?php $this->endWidget(); ?>
    <!--        </div>
        </div>-->

</div>
<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();

        document.body.innerHTML = originalContents;
    }
</script>