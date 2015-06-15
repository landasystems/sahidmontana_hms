<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printGlobal()" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_global" style="text-align: center;width: 100%">
    <center><h3>GLOBAL AUDIT TRANSACTION</h3></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">
    <table style="width:100%" class="items table table-striped table-condensed">
        <thead>
            <tr>
                <th class="span1" style="text-align: center">NO</th>
                <th class="span3" style="text-align: left">TRANSACTION</th>            
                <th class="span2" style="text-align: right">CASH</th>            
                <th class="span2" style="text-align: right">CREDIT</th>            
                <th class="span2" style="text-align: right">GUEST LEDGER</th>            
                <th class="span2" style="text-align: right">CITY LEDGER</th>            
                <th class="span2" style="text-align: right">REVENUE</th>            
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
            $filterRoomBill = array_filter($naDet, function($naDet) {
                return $naDet['room_bill_id'] != 0;
            });

            foreach ($filterRoomBill as $data) {
                if ($data->RoomBill->charge != 0) {
                    $pax = (!empty($data->RoomBill->pax)) ? $data->RoomBill->pax : 0;
                    $extrabed = (!empty($data->RoomBill->extrabed)) ? $data->RoomBill->extrabed : 0;
                    $extrabed_price = (!empty($data->RoomBill->extrabed_price)) ? $data->RoomBill->extrabed_price : 0;

                    $tot_fnb += $pax * $charge_pax;
                    $tot_xbed += $extrabed * $extrabed_price;
                    $totRoomCharge += $data->RoomBill->charge - ($pax * $charge_pax) - ($extrabed * $extrabed_price);

//                    if ($data->RoomBill->package_room_type_id != 0) {
//                        $package = json_decode($roomType[$data->RoomBill->package_room_type_id]['charge_additional_ids']);
//                        if (!empty($package)) {
//                            foreach ($package as $mPackage) {
//                                $root = getRootId($mPackage->id, $additional, $category, $category_all);
//                                $departement[$root]['gl'] += $mPackage->amount * $additional[$mPackage->id]['charge'];
//                                $totRoomCharge -= $mPackage->amount * $additional[$mPackage->id]['charge'];
//                            }
//                        }
//                    }
                    if ($data->RoomBill->others_include != '') {
                        $others_include = json_decode($data->RoomBill->others_include);
                        foreach ($others_include as $mInclude) {
                            $tuyul = ChargeAdditional::model()->findByPk($mInclude);
                            $root = getRootId($tuyul->id, $additional, $category, $category_all);
                            $departement[$root]['gl'] += $tuyul->charge;
                            $totRoomCharge -= $tuyul->charge;
                        }
                    }
                }
                //tagian transaksi outlet dari masing2 roombill (roombill sudah pasti belum checkout)
                $roomBillId = $data->RoomBill->id;
                $filterBillChargeAll = array_filter($billChargeAll, function($billChargeAll) use($roomBillId) {
                    return $billChargeAll['gl_room_bill_id'] == $roomBillId;
                });
                foreach ($filterBillChargeAll as $chargeAll) {
                    $s = BillChargeDet::model()->findByAttributes(array('bill_charge_id' => $chargeAll->id));
                    $departement[$s->Additional->ChargeAdditionalCategory->root]['gl'] += $chargeAll->gl_charge;
                }
            }



            //looping transaction                  
            $filterBillCharge = array_filter($naDet, function($naDet) {
                return $naDet['bill_charge_id'] != 0;
            });
            foreach ($filterBillCharge as $charge) {
                //khusus guest ledger, di hendle oleh $filterBillChargeAll
                $s = BillChargeDet::model()->findByAttributes(array('bill_charge_id' => $charge->BillCharge->id));
                $departement[$s->Additional->ChargeAdditionalCategory->root]['cash'] += $charge->BillCharge->cash;
                $departement[$s->Additional->ChargeAdditionalCategory->root]['credit'] += $charge->BillCharge->cc_charge;
                $departement[$s->Additional->ChargeAdditionalCategory->root]['cl'] += $charge->BillCharge->ca_charge;
            }

            //looping deposite  
            $filterDeposite = array_filter($naDet, function($naDet) {
                return $naDet['deposite_id'] != 0;
            });
            foreach ($filterDeposite as $dp) {
                if ($dp->Deposite->dp_by == 'cash') {
                    $totDpCash += $dp->Deposite->amount;
                } elseif ($dp->Deposite->dp_by == 'cc') {
                    $totDpCredit += $dp->Deposite->amount;
                } elseif ($dp->Deposite->dp_by == 'debit') {
                    $totDpCredit += $dp->Deposite->amount;
                } elseif ($dp->Deposite->dp_by == 'bank') {
                    $totDpBank += $dp->Deposite->amount;
                } elseif ($dp->Deposite->dp_by == 'ca') {
                    $totDpCa += $dp->Deposite->amount;
                }
            }

            //looping deposite used
            $total_dp_used = 0;
            foreach ($naDpUsed as $dp_used) {
                $total_dp_used += $dp_used->Deposite->amount;
            }

            //looping Gpay   
            $filterBill = array_filter($naDet, function($naDet) {
                return $naDet['bill_id'] != 0;
            });
            foreach ($filterBill as $b) {
                $totGPcash += $b->Bill->cash;
                $totGPCredit += $b->Bill->cc_charge;
                $totGPCa += $b->Bill->ca_charge;

                if ($b->Bill->total < 0) {
                    $totGPcash -= $b->Bill->refund;
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
            echo '<td style="text-align:right">' . landa()->rp($total_dp_used * -1) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($total_dp_used * -1) . '</td>';
            echo '</tr>';
            $totAll -=$total_dp_used;
            $totGL -= $total_dp_used;
            $no++;
            ?>
            <tr>
                <th colspan="" style="text-align: left"></th>
                <th colspan="" style="text-align: left"><b>Total</b></th>
                <th style="text-align: right">
                    <?php echo landa()->rp($totCash); ?>
                    <input type="hidden" name="Na[global_cash]" value="<?php echo $totCash; ?>" />
                </th>
                <th style="text-align: right">
                    <?php echo landa()->rp($totCC); ?>
                    <input type="hidden" name="Na[global_cc]" value="<?php echo $totCC; ?>" />
                </th>
                <th style="text-align: right">
                    <?php echo landa()->rp($totGL); ?>
                    <input type="hidden" name="Na[global_gl]" value="<?php echo $totGL; ?>" />
                </th>
                <th style="text-align: right">
                    <?php echo landa()->rp($totCL); ?>
                    <input type="hidden" name="Na[global_cl]" value="<?php echo $totCL; ?>" />
                </th>            
                <th style="text-align: right">
                    <?php echo landa()->rp($totAll); ?>
                    <input type="hidden" name="Na[global_total]" value="<?php echo $totAll; ?>" />
                </th>            
            </tr>


        </tbody>
    </table>
    <table style="width: 100%">
        <tr>
            <td style="padding: 0px;width: 30%" class="span2">Audit By</td>        
            <td style="padding: 0px">: <?php echo $model->Cashier->name; ?></td>
        </tr>    
        <tr>
            <td style="padding: 0px">Printed Time</td>        
            <td style="padding: 0px">: <?php echo date('l d-M-Y H:i:s', strtotime($model->created)); ?></td>
        </tr>    
    </table>
</div>

<style type="text/css">
    @media print
    {
        body {visibility:hidden;}
        .na_global{visibility: visible;} 
        .na_global{width: 100%;top: 0px;left: 0px;position: absolute;} 

    }
</style>
<script type="text/javascript">
    function printGlobal()
    {
        window.print();
    }
</script>
