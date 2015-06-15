<style>
    th {
        vertical-align: middle !important;
    }
</style>
<center><h3>GUEST IN HOUSE</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>
<table class="items table table-striped table-hover table-bordered table-condensed">
    <thead>
        <tr>
            <th style="width: 50px;text-align: center">#</th>
            <th class="span1"  style="text-align: center">Room</th>
            <th class="span2"  style="text-align: center">Room Type</th>            
            <th class="span4"  style="text-align: center">Guest Name</th>                                                     
            <th class="span4"  style="text-align: center">Address</th>                                                     
            <th class="span2"  style="text-align: center">Mark. Seg</th>    
            <th class="span2"  style="text-align: center">Arrival</th>                        
            <th class="span2"  style="text-align: center">Departure</th> 
            <!--<th class="span2"  style="text-align: center">Room Charge</th>-->            
            <th class="span2"  style="text-align: center">EB</th>                                               
            <th class="span1"  style="text-align: center">PAX</th>                        
            <th class="span2"  style="text-align: center">Room Charge</th>  
            <th class="span2"  style="text-align: center">BF</th>   
            <th class="span5"  style="text-align: center">Other Includes</th>   
            <th class="span2"  style="text-align: center">Room Rate</th>   

            <?php
//            $others_include = json_decode($siteConfig->others_include);
//            foreach ($others_include as $mInclude) {
//                $tuyul = ChargeAdditional::model()->findByPk($mInclude);
//                echo '<th class="span2" rowspan="2" style="text-align: center">' . $tuyul->name . '</th>  ';
//                $total[$tuyul->id] = 0;
//            }
            ?>


            <th class="span2" style="text-align: center">Remarks</th>                        
        </tr>                                            
    </thead>
    <tbody>
        <?php
//from siteconfig        
        $settings = json_decode($siteConfig->settings, true);
        $totRoomRate = 0;
        $totEB = 0;
        $totRoomCharge = 0;
        $totPax = 0;
        $totBF = 0;
        $totOther = 0;
        $totRoomRate = 0;
        $no = 0;
        foreach ($roomBills as $r) {
            if ($r->is_checkedout == 0) {
                $txtOther = '';
                $charge_pax = (isset($r->fnb_price)) ? $r->fnb_price : 0;
                $type = ($r->charge == 0) ? ' [' . ucwords($r->Registration->type) . ']' : '';
                if ($r->charge == 0) {
                    $rate = 0;
                    $charge = 0;
                    $extrabed = $r->extrabed * $r->extrabed_price;
                    $bf = $r->pax * $charge_pax;
                } else {
                    $charge = $r->room_price;
                    $extrabed = $r->extrabed * $r->extrabed_price;
                    $bf = $r->pax * $charge_pax;
                    $rate = $charge + $extrabed + $bf;
//                    if ($r->package_room_type_id != 0) {
//                        $package = json_decode($roomType[$r->package_room_type_id]['charge_additional_ids']);
//                        if (!empty($package)) {
//                            foreach ($package as $mPackage) {
//                                $rate += $mPackage->total;
//                            }
//                        }
//                    }
                    //other includes
                    if ($r->others_include != '') {
                        $others_include = json_decode($r->others_include);
                        foreach ($others_include as $key => $mInclude) {
                            //mencari nama charge
                            $tuyul = ChargeAdditional::model()->findByPk($key);
                            $txtOther .= $tuyul->name . ' : ' . landa()->rp($mInclude, false) . '<br>';
                            
                            $rate += $mInclude;
                            $totOther += $mInclude;
                        }
                    }
                }
                $address = ($r->Registration->Guest->nationality == 'ID' || $r->Registration->Guest->nationality == '' || strtolower($r->Registration->Guest->nationality) == 'indonesia' ) ? $r->Registration->Guest->City->name : $r->Registration->Guest->nationality;
                $no++;
                echo '<tr>';
                echo '<td style="text-align:center">' . $no . '</td>';
                echo '<td style="text-align:center">' . $r->Room->number . '</td>';
                echo '<td style="text-align:left">' . $r->Room->RoomType->name . '</td>';
                echo '<td style="text-align:left">' . $r->Registration->Guest->guestName . $type . '</td>';
                echo '<td style="text-align:left">' . $address . '</td>';
                echo '<td style="text-align:left">' . $r->Registration->MarketSegment->name . '</td>';
                echo '<td style="text-align:left">' . date('d-m-Y', strtotime($r->Registration->date_from)) . '</td>';
                echo '<td style="text-align:left">' . date('d-m-Y', strtotime($r->maxDate)) . '</td>';
//                echo '<td style="text-align:right">' . landa()->rp($rate) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($extrabed, false) . '</td>';
                echo '<td style="text-align:right">' . $r->pax . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($charge, false) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($bf, false) . '</td>';
                $other = 0;

                //other include
//                $others_include = json_decode($siteConfig->others_include);
//                foreach ($others_include as $mInclude) {
//                    $mOther = json_decode($r->others_include, true);
//                    $tuyul = ChargeAdditional::model()->findByPk($mInclude);
//                    if ($r->charge == 0) {
//                        if ($r->others_include != '') {
//                            $txtOther .= $tuyul->name . ' : ' . landa()->rp(0, false) . '<br>';
//                        }
//                    } else {
//                        if ($r->others_include != '') {
//                            if (isset($mOther[$mInclude])) {
//                                $tuyul = ChargeAdditional::model()->findByPk($mInclude);
//                                $other += $mOther[$mInclude];
//                                $txtOther .= $tuyul->name . ' : ' . landa()->rp($mOther[$mInclude], false) . '<br>';
////                                $total[$tuyul->id] += $mOther[$mInclude];
//                                $totOther += $mOther[$mInclude];
//                            }
//                        }
//                    }
//                }
                //package
//                $totalPackage = 0;
//                if ($r->package_room_type_id != 0) {
//                    $package = json_decode($roomType[$r->package_room_type_id]['charge_additional_ids']);
//                    if (!empty($package)) {
//                        foreach ($package as $mPackage) {
//                            $tuyul = ChargeAdditional::model()->findByPk($mPackage->id);
//                            $txtOther .= $tuyul->name . ' : ' . landa()->rp($mPackage->amount * $mPackage->charge, false) . '<br>';
//                            $other += $mPackage->amount * $mPackage->charge;
//                            $totalPackage += $mPackage->amount * $mPackage->charge;
//                            $totOther += $mPackage->amount * $mPackage->charge;
//                        }
//                    }
//                }


                echo '<td style="text-align:left">' . $txtOther . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($r->charge, false) . '</td>';
                echo '<td style="text-align:left">' . strip_tags($r->Registration->remarks) . '</td>';
                echo '</tr>';
                $totRoomRate += $rate;
                $totEB += $extrabed;
                $totRoomCharge += $charge;
                $totPax += $r->pax;
                $totBF +=$bf;
//                $totRoomRate+= $r->charge;
            }
        }
        ?>
        <tr>
            <td colspan="7" style="text-align: right">Total :</td>
            <td style="text-align: right"><?php // echo landa()->rp($totRoomRate)                   ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totEB, false) ?></td>                                         
            <td style="text-align: right"><?php echo $totPax ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totRoomCharge, false) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totBF, false) ?></td>                             
            <td style="text-align: left"><?php echo landa()->rp($totOther, false) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totRoomRate, false) ?></td>                                        
            <td style="text-align: right"></td>                             

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
