<table>
    <tr>
        <td  align="center" colspan="9"><font size="4">CASHIER SHEET</font><br>
            <?php echo date("l Y-F-d"); ?>
        </td>
    </tr> 
</table>
<br>
<table border="1">

    <tr>
        <th class="span1" rowspan="2" valign="middle">No.</th>
        <th class="span2" rowspan="2" valign="middle">Type</th>
        <th class="span2" rowspan="2" valign="middle">Code Transaction</th>                    
        <th class="span2" colspan="2" valign="middle">Cash</th>
        <th class="span2" rowspan="2" valign="middle">Guest Ledger</th>
        <th class="span2" rowspan="2" valign="middle">Credit Card</th>
        <th class="span2" rowspan="2" valign="middle">C/A</th>                                                        
        <th class="span2" rowspan="2" valign="middle">Remarks</th>                                                        
    </tr>
    <tr >
        <th class="span2">Tunai</th>
        <th class="span2">Refund DP</th>                                                    
    </tr>                        


    <?php
    $detail = BillCashierDet::model()->findAll(array('condition' => 'bill_cashier_id=' . $id));
    $bills = BillCashier::model()->findByPk($id);
    $approved = (!empty($bills->approved_user_id)) ? $bills->Approved->name : '';
    $tot_cash = 0;
    $tot_refund = 0;
    $tot_credit = 0;
    $tot_ca = 0;
    $tot_gl = 0;
    $no = 1;
    foreach ($detail as $det) {
        if (!empty($det->bill_id)) {
            //************** guest billing *******************//
            $bill = Bill::model()->findByPk($det->bill_id);
            $cash = ($bill->total > 0) ? $bill->cash - $bill->refund : $bill->cash;
            $refund = ($bill->total < 0 && $bill->refund > 0) ? $bill->refund : 0;
            $credit = $bill->cc_charge;
            $ca = $bill->ca_charge;

            $tot_cash += $cash;
            $tot_refund += ($bill->total < 0 && $bill->refund > 0) ? $refund : 0;
            $tot_credit += $credit;
            $tot_ca += $ca;

            $row = '<tr>';
            $row .= '<td align="center">' . $no . '</td>';
            $row .= '<td align="left">' . 'Guest Checkedout' . '</td>';
            $row .= '<td align="left">' . $bill->code . '</td>';
            $row .= '<td align="right">' . landa()->rp($cash) . '</td>';
            $row .= '<td align="right">' . landa()->rp($refund) . '</td>';
            $row .= '<td align="right">' . landa()->rp(0) . '</td>';
            $row .= '<td align="right">' . landa()->rp($credit) . '</td>';
            $row .= '<td align="right">' . landa()->rp($ca) . '</td>';
            $row .= '<td align="left">' . $bill->description . '</td>';
            $row .= '</tr>';
            echo $row;
            $no++;
        } elseif (!empty($det->deposite_id)) {
            //************** guest deposite *******************//
            $dp = Deposite::model()->findByPk($det->deposite_id);
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
            $tipe = ($dp->is_walk_in_cash == 1) ? "Walk In Cash" : "Guest Deposite";

            $row = '<tr>';
            $row .= '<td align="center">' . $no . '</td>';
            $row .= '<td align="left">' . $tipe . '</td>';
            $row .= '<td align="left">' . $dp->code . '</td>';
            $row .= '<td align="right">' . landa()->rp($cash) . '</td>';
            $row .= '<td align="right">' . landa()->rp($refund) . '</td>';
            $row .= '<td align="right">' . landa()->rp(0) . '</td>';
            $row .= '<td align="right">' . landa()->rp($credit) . '</td>';
            $row .= '<td align="right">' . landa()->rp($ca) . '</td>';
            $row .= '<td align="left">' . $dp->description . '</td>';
            $row .= '</tr>';
            echo $row;
            $no++;
        } elseif (!empty($det->bill_charge_id)) {
            //************** guest transaction *******************//
            $trans = BillCharge::model()->findByPk($det->bill_charge_id);
            $transDet = BillChargeDet::model()->findByAttributes(array('bill_charge_id' => $trans->id));
            $cash = ($trans->total > 0) ? $trans->cash - $trans->refund : $trans->cash;
            $refund = 0;
            $credit = $trans->cc_charge;
            $ca = $trans->ca_charge;
            $gl = $trans->gl_charge;



            $tot_cash += $cash;
            $tot_refund += 0;
            $tot_credit += $credit;
            $tot_ca += $ca;
            $tot_gl += $gl;

            $row = '<tr>';
            $row .= '<td align="center">' . $no . '</td>';
            $row .= '<td align="left">' . $trans->ChargeAdditionalCategory->name . '</td>';
            $row .= '<td align="left">' . $trans->code . '</td>';
            $row .= '<td align="right">' . landa()->rp($cash) . '</td>';
            $row .= '<td align="right">' . landa()->rp($refund) . '</td>';
            $row .= '<td align="right">' . landa()->rp($gl) . '</td>';
            $row .= '<td align="right">' . landa()->rp($credit) . '</td>';
            $row .= '<td align="right">' . landa()->rp($ca) . '</td>';
            $row .= '<td align="left">' . $trans->description . '</td>';
            $row .= '</tr>';
            echo $row;
            $no++;
        }
    }
    ?>
    <tr style="height:40px !important;">
        <th align="left" colspan="3"><b>TOTAL</b></th>                            
        <th align="right"><?php echo landa()->rp($tot_cash); ?></th>
        <th align="right"><?php echo landa()->rp($tot_refund); ?></th>
        <th align="right"><?php echo landa()->rp($tot_gl); ?></th>
        <th align="right"><?php echo landa()->rp($tot_credit); ?></th>
        <th align="right"><?php echo landa()->rp($tot_ca); ?></th>
        <th align="right"></th>                            
    </tr>


</table>  
<br>
<table style="width:100%">
    <tr>
        <td class="span6" >Prepared By : <?php echo $bills->Cashier->name; ?></td>
        <td class="span6" Approved By : <?php echo $approved; ?></td>                        
    </tr>
</table>