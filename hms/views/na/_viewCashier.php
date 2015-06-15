<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_cashier')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_cashier" id="na_cashier">
    <center><h4>GENERAL CASHIER SHEET</h4   ></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">

    <table class="tbPrint" style="font-size: 10px;line-height: 10px !important;">
        <thead>
            <tr>
                <th class="span1" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" rowspan="2">No.</th>
                <th class="span2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" rowspan="2">Type</th>                              
                <th class="span2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" rowspan="2">Code Trans</th>                              
                <th class="span2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" rowspan="2">Room Number</th>                              
                <th class="span2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" colspan="2">Cash</th>
                <th class="span2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" rowspan="2">C. Card</th>
                <th class="span2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" rowspan="2">G. Ledger</th>
                <th class="span2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" rowspan="2">C/A</th>                                                                    
                <th class="span2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" rowspan="2">Remarks</th>                                                                    
                <th class="span1" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" rowspan="2">Cashier</th>                                                                    
            </tr>
            <tr >
                <th style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px" class="span2">Tunai</th>
                <th style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px"    class="span2">Refund DP</th>                                                    
            </tr>                        
        </thead>
        <tbody>
            <?php
            //************** guest billing *******************//
            $bill = array_filter($naDet, function($naDet) {
                return $naDet['bill_id'] != 0;
            });
            $bills = $bill;
//        $bills = Bill::model()->findAll(array('condition' => 'created_user_id=' . Yii::app()->user->id . ' and is_cashier=0'));
            $tot_cash = 0;
            $tot_refund = 0;
            $tot_credit = 0;
            $tot_ca = 0;
            $tot_gl = 0;
            $no = 1;
            $room = array();
            foreach ($bills as $bill) {
                $billdate = BillDet::model()->findAll(array('with' => 'RoomBill', 'condition' => 'RoomBill.lead_room_bill_id=0 AND bill_id=' . $bill->Bill->id));
                $room_number = array();
                foreach ($billdate as $m) {
                    $room_number[] = $m->RoomBill->room_number;
                }
                $room = (empty($room_number)) ? '-' : implode(', ', $room_number);

                $cash = ($bill->Bill->total > 0) ? $bill->Bill->cash - $bill->Bill->refund : $bill->Bill->cash;
                $refund = ($bill->Bill->total < 0 && $bill->Bill->refund > 0) ? $bill->Bill->refund : 0;
                $credit = $bill->Bill->cc_charge;
                $ca = $bill->Bill->ca_charge;

                $tot_cash += $cash;
                $tot_refund += ($bill->Bill->total < 0 && $bill->Bill->refund > 0) ? $refund : 0;
                $tot_credit += $credit;
                $tot_ca += $ca;

                $row = '<tr>';
                $row .= '<td class="print2" style="text-align:center;border-bottom:none;border-top:none">' . $no . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . 'Guest Checkedout' . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $bill->Bill->code . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . $room . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($cash, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($refund, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($credit, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp(0, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ca, false) . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $bill->Bill->description . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $bill->Bill->Cashier->name . '</td>';
                $row .= '</tr>';
                echo $row;
                $no++;
            }
            ?>
            <?php
            //************** guest deposite *******************//

            /* $deposite = array_filter($naDet, function($naDet) {
              return $naDet['deposite_id'] != 0;
              }); */
            $deposit = Deposite::model()->with('NaDet')->findAll(array('condition' => 't.id = NaDet.deposite_id and NaDet.na_id=' . $model->id));

//        $deposit = Deposite::model()->findAll(array('condition' => 'created_user_id=' . Yii::app()->user->id . ' and is_cashier=0'));
            foreach ($deposit as $dp) {
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

                //mencari room number
//            $regModel = RegistrationDetail::model()->findAll(array('condition'=>'deposite_id='.$dp->id));
//            $temproom_number = array();
//            foreach ($regModel as $regArr){
//                $temproom_number = 
//            }
                
                //mencari deposit registrasi                
                $room = '-';
                $room_number = array();
                $is_regis = Registration::model()->find(array('condition' => 'deposite_id=' . $dp->id));
                if (isset($is_regis)) {
                    $roomDet = RegistrationDetail::model()->findAll(array('condition' => 'registration_id=' . $is_regis->id));
                    foreach ($roomDet as $roomNumber) {
                        $room_number[] = $roomNumber->room_id;
                    }
                }
                
                $room = '';
                $is_reservation = Reservation::model()->find(array('condition' => 'deposite_id=' . $dp->id));
                if (isset($is_reservation)) {
                    $roomDet = ReservationDetail::model()->findAll(array('condition' => 'reservation_id=' . $is_reservation->id));
                    foreach ($roomDet as $roomNumber) {
                        $room_number[] = $roomNumber->room_id;
                    }
                }
                
                $room = (empty($room_number)) ? '-' : implode(', ', $room_number);

                $tot_cash += $cash;
                $tot_refund += 0;
                $tot_credit += $credit;
                $tot_ca += $ca;
                $tipe = "Guest Deposite";
//                $tipe = ($dp->is_walk_in_cash == 1) ? "Walk In Cash" : "Guest Deposite";

                $row = '<tr>';
                $row .= '<td class="print2" style="text-align:center;border-bottom:none;border-top:none">' . $no . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $tipe . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $dp->code . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . $room . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($cash, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($refund, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($credit, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp(0, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ca, false) . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $dp->description . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $dp->Cashier->name . '</td>';
                $row .= '</tr>';
                echo $row;
                $no++;
            }
            ?>


            <?php
            //************** guest transaction *******************//
//        $category = chtml::listData(ChargeAdditionalCategory::model()->findAll(), 'id', 'name');
//        $transaction = BillCharge::model()->findAll(array('condition' => 'created_user_id=' . Yii::app()->user->id . ' and is_cashier=0 AND is_temp=0'));
            $billCharge = array_filter($naDet, function($naDet) {
                return $naDet['bill_charge_id'] != 0;
            });
            $category = $category;
            $transaction = $billCharge;
            $roombill;
            foreach ($transaction as $trans) {
//            $roombill = RoomBill::model()->findAll(array('condition' => 'id=' . $trans->gl_room_bill_id));
                $room = (isset($trans->BillCharge->RoomBill->room_number)) ? $trans->BillCharge->RoomBill->room_number : '-';
                $cash = ($trans->BillCharge->total > 0) ? $trans->BillCharge->cash - $trans->BillCharge->refund : $trans->BillCharge->cash;
                $refund = 0;
                $credit = $trans->BillCharge->cc_charge;
                $ca = $trans->BillCharge->ca_charge;
                $gl = $trans->BillCharge->gl_charge;
                $glroom = $trans->BillCharge->gl_room_bill_id;
                $type = ChargeAdditionalCategory::model()->findByPk($trans->BillCharge->ChargeAdditionalCategory->root);


                $tot_cash += $cash;
                $tot_refund += 0;
                $tot_credit += $credit;
                $tot_ca += $ca;
                $tot_gl += $gl;

                $sName = (isset($trans->BillCharge->Cashier->name)) ? $trans->BillCharge->Cashier->name : '';
                $row = '<tr>';
                $row .= '<td class="print2" style="text-align:center;border-bottom:none;border-top:none">' . $no . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $type->name . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $trans->BillCharge->code . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . $room . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($cash, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($refund, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($credit, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($gl, false) . '</td>';
                $row .= '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ca, false) . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $trans->BillCharge->description . '</td>';
                $row .= '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $sName . '</td>';
                $row .= '</tr>';
                echo $row;
                $no++;
            }
            ?>

            <tr style="height:40px !important;">
                <th class="print2" style="text-align: left !important" colspan="4"><b>TOTAL</b></th>                            
                <th class="print2" style="text-align: right !important"><?php echo landa()->rp($tot_cash, false); ?></th>
                <th class="print2" style="text-align: right !important"><?php echo landa()->rp($tot_refund, false); ?></th>
                <th class="print2" style="text-align: right !important"><?php echo landa()->rp($tot_credit, false); ?></th>
                <th class="print2" style="text-align: right !important"><?php echo landa()->rp($tot_gl, false); ?></th>
                <th class="print2" style="text-align: right !important"><?php echo landa()->rp($tot_ca, false); ?></th>                                  
                <th class="print2" style="text-align: right !important"></th>                                  
                <th class="print2" style="text-align: right !important"></th>                                  
            </tr>
        </tbody>
    </table>

    <br>
    <table>
        <tr>
            <td style="padding: 0px;width: 30%;font-size: 10px;" class="span2">Audit By</td>        
            <td style="padding: 0px;font-size: 10px;">: <?php echo $model->Cashier->name; ?></td>
        </tr>    
        <tr>
            <td style="padding: 0px;font-size: 10px;">Printed Time</td>        
            <td style="padding: 0px;font-size: 10px;">: <?php echo date('l d-M-Y H:i:s', strtotime($model->created)); ?></td>
        </tr>    
    </table>
</div>


<style type="text/css">
    @media print
    {
        body {visibility:hidden;}
        .na_cashier{visibility: visible;} 
        .na_cashier{width: 100%;top: 0px;left: 0px;position: absolute;} 
        .print2 {
            padding: 3px;
            line-height: 6px;
            font-size: 10px;
            vertical-align: middle;
            word-spacing: 1.1pt;
            letter-spacing: 4pt;
            color: #000;
        }

    }
</style>
