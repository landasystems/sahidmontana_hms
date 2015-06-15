<center><h3>REPORT DAILY REPORTS PER COVER</h3></center>
<center>Date Night Audit: <?php echo date('l Y-m-d H:i:s'); ?></center>
<hr>
<?php
$category = ChargeAdditionalCategory::model()->findAll(array('condition' => 'level=1'));
?>

<table class="items table table-striped table-bordered table-condensed">
    <?php
    //from siteconfig
    $siteConfig = SiteConfig::model()->findByPk(1);
    $settings = json_decode($siteConfig->settings, true);
    $charge_extrabed = (!empty($settings['extrabed_charge'])) ? $settings['extrabed_charge'] : '';
    $extrabed_dep_id = (!empty($settings['extrabed_departement'])) ? $settings['extrabed_departement'] : '';
    $charge_pax = (!empty($settings['fb_charge'])) ? $settings['fb_charge'] : '';
    $pax_dep_id = (!empty($settings['fb_departement'])) ? $settings['fb_departement'] : '';
    $totAllCash = 0;
    $totAllCredit = 0;
    $totAllGL = 0;
    $totAllCL = 0;

    $dateRange = explode('-', $date);
    $date_from = date("Y/m/d", strtotime($dateRange[0]));
    $date_to = date("Y/m/d", strtotime($dateRange[1])) . " 23:59:59";

    foreach ($category as $departement) {
        ?>

        <thead>            
            <tr><th colspan="8"><?php echo strtoupper($departement->name); ?></th></tr>
            <tr>
                <th class="span1" style="text-align: center">NO</th>
                <th class="span5" style="text-align: center">ITEM NAME</th>            
                <th class="span2" style="text-align: center">ROOM</th>            
                <!--<th class="span3" style="text-align: center">GUEST</th>-->            
                <th class="span3" style="text-align: center">CASH</th>            
                <th class="span3" style="text-align: center">DEBIT/CREDIT</th>            
                <th class="span3" style="text-align: center">GUEST LEDGER</th>            
                <th class="span3" style="text-align: center">CITY LEDGER</th>            
                <th class="span3" style="text-align: center">CASHIER</th>            
            </tr>                        
        </thead>
        <tbody>
            <?php
            $totCash = 0;
            $totCredit = 0;
            $totGL = 0;
            $totCL = 0;
            $no = 1;

            $roomBill = RoomBill::model()->findAll(array('condition' => 'is_checkedout=0', 'group' => 'registration_id'));
            if (!empty($roomBill)) {
                foreach ($roomBill as $mRoomBill) {
                    //find all from this folio                    
                    $roomBillOk = RoomBill::model()->findAll(array('condition' => 'registration_id=' . $mRoomBill->registration_id . ' and processed=1'));
                    foreach ($roomBillOk as $data) {
                        if ($departement->id == $pax_dep_id) {
                            //fnb from roombil (include)
                            echo '<tr>';
                            echo '<td style="text-align: center">' . $no . '</td>';
                            echo '<td>Breakfast</td>';
                            echo '<td style="text-align: center">' . $data->room_number . '</td>';
//                            echo '<td>' . $data->Registration->Guest->name . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp($data->pax * $charge_pax) . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                            echo '<td >' . ucwords($data->Registration->User->name) . '</td>';
                            echo '</tr>';
                            $no++;
                            $totGL += $data->pax * $charge_pax;
                            $totAllGL += $data->pax * $charge_pax;

                            //fnb from additional charge
                            $roomBillDet = RoomBillDet::model()->findAll(array('condition' => 'room_bill_id=' . $data->id));
                            foreach ($roomBillDet as $s) {
                                if ($s->Additional->ChargeAdditionalCategory->root == $pax_dep_id) {
                                    echo '<tr>';
                                    echo '<td style="text-align: center">' . $no . '</td>';
                                    echo '<td>' . $s->Additional->name . '</td>';
                                    echo '<td style="text-align: center">' . $data->room_number . '</td>';
                                    echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                    echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                    echo '<td style="text-align:right">' . landa()->rp($s->amount * $s->charge) . '</td>';
                                    echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                    echo '<td>' . ucwords($s->Cashier->name) . '</td>';
                                    echo '</tr>';
                                    $no++;
                                    $totGL += $s->amount * $s->charge;
                                    $totAllGL += $s->amount * $s->charge;
                                }
                            }
                        } elseif ($departement->id == $extrabed_dep_id) {
                            //extrabed
                            if ($data->extrabed != 0 || !empty($data->extrabed)) {
                                echo '<tr>';
                                echo '<td style="text-align: center">' . $no . '</td>';
                                echo '<td>Extra Bed</td>';
                                echo '<td style="text-align: center">' . $data->room_number . '</td>';
//                                echo '<td>' . $data->Registration->Guest->name . '</td>';
                                echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                echo '<td style="text-align:right">' . landa()->rp($data->extrabed * $charge_extrabed) . '</td>';
                                echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                echo '<td >' . ucwords($data->Registration->User->name) . '</td>';
                                echo '</tr>';
                                $no++;
                                $totGL += $data->extrabed * $charge_extrabed;
                                $totAllGL += $data->extrabed * $charge_extrabed;
                            }
                        } else {
                            $roomBillDet = RoomBillDet::model()->findAll(array('condition' => 'room_bill_id=' . $data->id));
                            foreach ($roomBillDet as $s) {
                                if ($s->Additional->ChargeAdditionalCategory->root == $departement->id) {
                                    echo '<tr>';
                                    echo '<td style="text-align: center">' . $no . '</td>';
                                    echo '<td>' . $s->Additional->name . '</td>';
                                    echo '<td style="text-align: center">' . $data->room_number . '</td>';
                                    echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                    echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                    echo '<td style="text-align:right">' . landa()->rp($s->amount * $s->charge) . '</td>';
                                    echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                    echo '<td>' . ucwords($s->Cashier->name) . '</td>';
                                    echo '</tr>';
                                    $no++;
                                    $totGL += $s->amount * $s->charge;
                                    $totAllGL += $s->amount * $s->charge;
                                }
                            }
                        }

                        //get from package
                        if ($data->package_room_type_id != 0) {
                            $roomPackage = RoomType::model()->findByPk($data->package_room_type_id);
                            $package = json_decode($roomPackage->charge_additional_ids);
                            if (!empty($package)) {
                                foreach ($package as $mPackage) {
                                    $additional = ChargeAdditional::model()->findByPk($mPackage->id);
                                    if ($additional->ChargeAdditionalCategory->root == $departement->id) {
                                        echo '<tr>';
                                        echo '<td style="text-align: center">' . $no . '</td>';
                                        echo '<td>[' . ucwords($data->Package->name) . '] ' . $additional->name . '</td>';
                                        echo '<td style="text-align: center">' . $data->room_number . '</td>';
                                        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                        echo '<td style="text-align:right">' . landa()->rp($mPackage->amount * $additional->charge) . '</td>';
                                        echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                                        echo '<td>' . ucwords($data->Cashier->name) . '</td>';
                                        echo '</tr>';
                                        $no++;
                                        $totGL += $mPackage->amount * $additional->charge;
                                        $totAllGL += $mPackage->amount * $additional->charge;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            //transaction from bill cash
            $billCash = BillCharge::model()->findAll(array('condition' => 'is_na=0'));
            foreach ($billCash as $mBillCash) {
                if ($mBillCash->by != 'gl') {
                    $billCashDet = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $mBillCash->id));
                    foreach ($billCashDet as $s) {
                        if ($s->Additional->ChargeAdditionalCategory->root == $departement->id) {
                            $mcash = 0;
                            $mcc = 0;
                            $mgl = 0;
                            $mca = 0;
                            $type = '';
                            if ($mBillCash->by == 'cash') {
                                $mcash = $s->amount * $s->charge;
                                $type = 'Cash';
                                $totCash += $s->amount * $s->charge;
                                $totAllCash += $s->amount * $s->charge;
                            } elseif ($mBillCash->by == 'cc') {
                                $mcc = $s->amount * $s->charge;
                                $type = 'Credit';
                                $totCredit += $s->amount * $s->charge;
                                $totAllCredit += $s->amount * $s->charge;
                            } elseif ($mBillCash->by == 'debit') {
                                $mcc = $s->amount * $s->charge;
                                $type = 'Debit';
                                $totCredit += $s->amount * $s->charge;
                                $totAllCredit += $s->amount * $s->charge;
                            } elseif ($mBillCash->by == 'ca') {
                                $mca = $s->amount * $s->charge;
                                $type = 'CL';
                                $totCL += $s->amount * $s->charge;
                                $totAllCL += $s->amount * $s->charge;
                            }

                            echo '<tr>';
                            echo '<td style="text-align: center">' . $no . '</td>';
                            echo '<td>' . $s->Additional->name . '</td>';
                            echo '<td style="text-align: center">' . $type . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp($mcash) . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp($mcc) . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp($mgl) . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp($mca) . '</td>';
                            echo '<td>' . ucwords($mBillCash->Cashier->name) . '</td>';
                            echo '</tr>';
                            $no++;
                        }
                    }
                }
            }
            ?>
            <tr>
                <td colspan="3" style="text-align: right">Total Departement <?php echo ucwords(strtolower($departement->name)); ?> :</td>
                <td style="text-align: right"><?php echo landa()->rp($totCash); ?></td>            
                <td style="text-align: right"><?php echo landa()->rp($totCredit); ?></td>            
                <td style="text-align: right"><?php echo landa()->rp($totGL); ?></td>            
                <td style="text-align: right"><?php echo landa()->rp($totCL); ?></td>            
                <td style="text-align: right"></td>            
            </tr> 
            <tr><td colspan="8">&nbsp;</td></tr>
        </tbody>

        <?
    }
    ?>
    <thead>  
        <tr>
            <th colspan="3" style="text-align: right">Total All Departement :</th>
            <th style="text-align: right"><?php echo landa()->rp($totAllCash); ?></th>            
            <th style="text-align: right"><?php echo landa()->rp($totAllCredit); ?></th>            
            <th style="text-align: right"><?php echo landa()->rp($totAllGL); ?></th>            
            <th style="text-align: right"><?php echo landa()->rp($totAllCL); ?></th>            
            <th style="text-align: right"></th>            
        </tr>
    </thead>
</table>