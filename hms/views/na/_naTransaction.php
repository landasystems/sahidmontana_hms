<center><h3>GLOBAL AUDIT TRANSACTION</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>
<table class="items table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th class="span1" style="text-align: center">NO</th>
            <th class="span5" style="text-align: center">TRANSACTION</th>            
            <th class="span2" style="text-align: center">CASH</th>            
            <th class="span2" style="text-align: center">CREDIT</th>            
            <th class="span2" style="text-align: center">GUEST LEDGER</th>            
            <th class="span2" style="text-align: center">CITY LEDGER</th>            
            <th class="span2" style="text-align: center">NET SALES</th>            
            <th class="span2" style="text-align: center">SERVICE CHARGE</th>            
            <th class="span2" style="text-align: center">TAX</th>            
            <th class="span2" style="text-align: center">GROSS SALES</th>            
        </tr>                        
    </thead>
    <tbody>
        <?php
        $totCash = 0;
        $totCC = 0;
        $totGL = 0;
        $totCL = 0;
        $totRoomCharge = 0;
        $totCCard = 0;
        $totDpCash = 0;
        $totDpCredit = 0;
        $totDpBank = 0;
        $totDpCa = 0;
        $totGPcash = 0;
        $totGPCredit = 0;
        $totGPCa = 0;
        $totAll = 0;
        $tot_fnb = 0;
        $tot_xbed = 0;

        //from siteconfig        
        $settings = json_decode($siteConfig->settings, true);
        $charge_extrabed = (!empty($settings['extrabed_charge'])) ? $settings['extrabed_charge'] : '';
        $charge_pax = (!empty($settings['fb_charge'])) ? $settings['fb_charge'] : '';
        $departement = array();
        foreach ($category as $a) {
            $departement[$a->id]['cash'] = 0;
            $departement[$a->id]['credit'] = 0;
            $departement[$a->id]['gl'] = 0;
            $departement[$a->id]['cl'] = 0;
        }
        $room_id = array();

        //room charge from room_bill where not checkout and then split it        
        foreach ($roomBills as $data) {
            if ($data->charge != 0) {
                $pax = (!empty($data->pax)) ? $data->pax : 0;
                $extrabed = (!empty($data->extrabed)) ? $data->extrabed : 0;
                $extrabed_price = (!empty($data->extrabed_price)) ? $data->extrabed_price : 0;

                $tot_fnb += $pax * $charge_pax;
                $tot_xbed += $extrabed * $extrabed_price;
                $totRoomCharge += $data->room_price;

//                if ($data->package_room_type_id != 0) {
//                    $package = json_decode($roomType[$data->package_room_type_id]['charge_additional_ids']);
//                    if (!empty($package)) {
//                        foreach ($package as $mPackage) {
//                            $root = getRootId($mPackage->id, $additional, $category, $category_all);
//                            $departement[$root]['gl'] += $mPackage->amount * $additional[$mPackage->id]['charge'];
//                            $totRoomCharge -= $mPackage->amount * $additional[$mPackage->id]['charge'];
//                        }
//                    }
//                }

                if ($data->others_include != '') {
                    $others_include = json_decode($data->others_include);
                    foreach ($others_include as $key => $mInclude) {
                        $root = getRootId($key, $additional, $category, $category_all);
                        $departement[$root]['gl'] += $mInclude;
                    }
                }
            }

            //tagian transaksi outlet dari masing2 roombill (roombill sudah pasti belum checkout)
            $roomBillId = $data->id;
            $filterBillChargeAll = array_filter($billChargeAll, function($billChargeAll) use($roomBillId) {
                return $billChargeAll['gl_room_bill_id'] == $roomBillId;
            });
            foreach ($filterBillChargeAll as $chargeAll) {
                $s = BillChargeDet::model()->findByAttributes(array('bill_charge_id' => $chargeAll->id));
                $departement[$s->Additional->ChargeAdditionalCategory->root]['gl'] += $chargeAll->gl_charge;
            }
        }

        //looping transaction        
        foreach ($billCharge as $charge) {
            //khusus guest ledger, di hendle oleh $filterBillChargeAll
            $s = BillChargeDet::model()->findByAttributes(array('bill_charge_id' => $charge->id));
            $departement[$s->Additional->ChargeAdditionalCategory->root]['cash'] += $charge->cash;
            $departement[$s->Additional->ChargeAdditionalCategory->root]['credit'] += $charge->cc_charge;
            $departement[$s->Additional->ChargeAdditionalCategory->root]['cl'] += $charge->ca_charge;
        }

        //looping deposite        
        foreach ($deposite as $dp) {
            if ($dp->dp_by == 'cash') {
                $totDpCash += $dp->amount;
            } elseif ($dp->dp_by == 'cc') {
                $totDpCredit += $dp->amount;
            } elseif ($dp->dp_by == 'debit') {
                $totDpCredit += $dp->amount;
            } elseif ($dp->dp_by == 'bank') {
                $totDpBank += $dp->amount;
            } elseif ($dp->dp_by == 'ca') {
                $totDpCa += $dp->amount;
            }
        }


        //looping deposite used
        $tot_dp_used = 0;
        foreach ($deposite_used as $dp_used) {
            $tot_dp_used += $dp_used->amount;
        }

        //looping Gpay        
        foreach ($bill as $b) {
            $totGPcash += $b->cash;
            $totGPCredit += $b->cc_charge;
            $totGPCa += $b->ca_charge;

            if ($b->total < 0) {
                $totGPcash -= $b->refund;
            }
        }

        echo '<tr>';
        echo '<td style="text-align:center">1</td>';
        echo '<td> Room Charge</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($totRoomCharge) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($totRoomCharge) . '</td>';
        echo '</tr>';

        $totAll += $totRoomCharge;
        $totGL += $totRoomCharge;

        echo '<tr>';
        echo '<td style="text-align:center">2</td>';
        echo '<td> Breakfast</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($tot_fnb) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($tot_fnb) . '</td>';
        echo '</tr>';
        $totAll += $tot_fnb;
        $totGL += $tot_fnb;


        echo '<tr>';
        echo '<td style="text-align:center">3</td>';
        echo '<td> Extrabed</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($tot_xbed) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($tot_xbed) . '</td>';
        echo '</tr>';
        $totAll += $tot_xbed;
        $totGL += $tot_xbed;

        echo '<tr>';
        echo '<td style="text-align:center">4</td>';
        echo '<td>General Payment</td>';
        echo '<td style="text-align:right">' . landa()->rp($totGPcash) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($totGPCredit) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($totGPCa) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($totGPCa + $totGPCredit + $totGPcash) . '</td>';
        echo '</tr>';
        $totAll +=$totGPCa + $totGPCredit + $totGPcash;
        $totCash += $totGPcash;
        $totCC += $totGPCredit;
        $totGL += 0;
        $totCL += $totGPCa;

        $no = 5;
        foreach ($category as $o) {
            echo '<tr>';
            echo '<td style="text-align:center">' . $no . '</td>';
            echo '<td>' . ucwords(strtolower($o->name)) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($departement[$o->id]['cash']) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($departement[$o->id]['credit']) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($departement[$o->id]['gl']) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($departement[$o->id]['cl']) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($departement[$o->id]['credit'] + $departement[$o->id]['cl'] + $departement[$o->id]['gl'] + $departement[$o->id]['cash']) . '</td>';
            echo '</tr>';
            $totAll += $departement[$o->id]['cl'] + $departement[$o->id]['gl'] + $departement[$o->id]['cash'] + $departement[$o->id]['credit'];
            $totCash += $departement[$o->id]['cash'];
            $totCC += $departement[$o->id]['credit'];
            $totGL += $departement[$o->id]['gl'];
            $totCL += $departement[$o->id]['cl'];
            $no++;
        }

        echo '<tr>';
        echo '<td style="text-align:center">' . $no . '</td>';
        echo '<td> Down Payment</td>';
        echo '<td style="text-align:right">' . landa()->rp($totDpCash) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($totDpCredit) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($totDpCash + $totDpCredit) . '</td>';
        echo '</tr>';
        $totAll += $totDpCash + $totDpCredit;
        $totCash += $totDpCash;
        $totCC += $totDpCredit;
        $totGL += 0;
        $totCL += 0;
        $no++;


        echo '<tr>';
        echo '<td style="text-align:center">' . $no . '</td>';
        echo '<td> Down Payment Applied</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($tot_dp_used * -1) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
        echo '<td style="text-align:right">' . landa()->rp($tot_dp_used * -1) . '</td>';
        echo '</tr>';
        $totAll -= $tot_dp_used;
        $totGL -= $tot_dp_used;
        $no++;
        ?>
        <tr>
            <td colspan="2" style="text-align: right"><b>Total Revenue</b></td>
            <td style="text-align: right">
                <?php echo landa()->rp($totCash / 1.21); ?>
                <input type="hidden" name="Na[global_cash]" value="<?php echo $totCash / 1.21; ?>" />
            </td>
            <td style="text-align: right">
                <?php echo landa()->rp($totCC / 1.21); ?>
                <input type="hidden" name="Na[global_cc]" value="<?php echo $totCC / 1.21; ?>" />
            </td>
            <td style="text-align: right">
                <?php echo landa()->rp($totGL / 1.21); ?>
                <input type="hidden" name="Na[global_gl]" value="<?php echo $totGL / 1.21; ?>" />
            </td>
            <td style="text-align: right">
                <?php echo landa()->rp($totCL / 1.21); ?>
                <input type="hidden" name="Na[global_cl]" value="<?php echo $totCL / 1.21; ?>" />
            </td>            
            <td style="text-align: right">
                <?php echo landa()->rp($totAll / 1.21); ?>
                <input type="hidden" name="Na[global_total]" value="<?php echo $totAll / 1.21; ?>" />
            </td>            
        </tr>


    </tbody>
</table>
<table>
    <tr>
        <td style="padding: 0px" class="span2">Audit By</td>        
        <td style="padding: 0px">: <?php echo User()->name; ?></td>
    </tr>    
    <tr>
        <td style="padding: 0px">Printed Time</td>        
        <td style="padding: 0px">: <?php echo date('l d-M-Y H:i:s'); ?></td>
    </tr>    
</table>