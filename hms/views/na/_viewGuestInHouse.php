<style>
    th {
        vertical-align: middle !important;
    }
</style>
<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_inHouse')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_inHouse" id="na_inHouse" style="width: 100%">
    <center><h4>GUEST IN HOUSE</h4></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">
    <table class="tbPrint" style="font-size: 10px;line-height: 9px;">
        <thead>
            <tr>
                <th class="span1 print2 judul"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Room</th>
                <th class="span2 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Room Type</th>            
                <th class="span4 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Guest Name</th>                                                     
                <th class="span4 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Address</th>                                                     
                <th class="span2 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Mark. Seg</th>    
                <th class="span2 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Arrival</th>                        
                <th class="span2 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Departure</th> 
                <!--<th class="span2"  style="text-align: center">Room Charge</th>-->            
                <th class="span1 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">EB</th>                                               
                <th class="span1 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">PAX</th>                        
                <th class="span1 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Room Charge</th>  
                <th class="span1 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">BF</th>   
                <th class="span3 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Other Includes</th>   
                <th class="span2 print2"  style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Room Rate</th>   
                <?php
                ?>
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Remarks</th>                        
            </tr>                                            
        </thead>
        <tbody>
            <?php
            //from siteconfig        
            $settings = json_decode($siteConfig->settings, true);
            $charge_extrabed = (!empty($settings['extrabed_charge'])) ? $settings['extrabed_charge'] : '';
            $charge_pax = (!empty($settings['fb_charge'])) ? $settings['fb_charge'] : '';

            $totRoomRate = 0;
            $totEB = 0;
            $totRoomCharge = 0;
            $totPax = 0;
            $totBF = 0;
            $totOther = 0;
            $totRoomRate = 0;

            $filterRoomBill = array_filter($naDet, function($naDet) {
                        return $naDet['room_bill_id'] != 0;
                    });

            foreach ($filterRoomBill as $r) {
                $charge_pax = (isset($r->RoomBill->fnb_price)) ? $r->RoomBill->fnb_price : 0;
                if ($r->is_checkedout == 0) {
                    $others_include = json_decode($siteConfig->others_include);
                    $type = ($r->RoomBill->charge == 0) ? ' [' . ucwords($r->RoomBill->Registration->type) . ']' : '';
                    if ($r->RoomBill->charge == 0) {
                        $rate = 0;
                        $charge = 0;
                        $extrabed = 0;
                        $bf = 0;
                    } else {
                        $charge = $r->RoomBill->room_price;
                        $extrabed = $r->RoomBill->extrabed * $r->RoomBill->extrabed_price;
                        $bf = $r->RoomBill->pax * $charge_pax;
                        $rate = $charge + $extrabed + $bf;
                        if ($r->RoomBill->package_room_type_id != 0) {
                            $package = json_decode($roomType[$r->RoomBill->package_room_type_id]['charge_additional_ids']);
                            if (!empty($package)) {
                                foreach ($package as $mPackage) {
                                    $rate += $mPackage->total;
                                }
                            }
                        }
                        //other includes
                        if ($r->RoomBill->others_include != '') {
                            $others_include = json_decode($r->RoomBill->others_include);
                            foreach ($others_include as $key => $mInclude) {
                                $rate += $mInclude;
                            }
                        }
                    }
                    $address = ($r->RoomBill->Registration->Guest->nationality == 'ID' || $r->RoomBill->Registration->Guest->nationality == '' || strtolower($r->RoomBill->Registration->Guest->nationality) == 'indonesia' ) ? $r->RoomBill->Registration->Guest->City->name : $r->RoomBill->Registration->Guest->nationality;
                    echo '<tr>';
                    echo '<td class="print2" style="text-align:center;border-bottom:none;border-top:none;line-height: 11px">' . $r->RoomBill->Room->number . '</td>';
                    echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none;line-height: 11px">' . $r->RoomBill->Room->RoomType->name . '</td>';
                    echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none;line-height: 11px">' . $r->RoomBill->Registration->Guest->guestName . $type . '</td>';
                    echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none;line-height: 11px">' . $address . '</td>';
                    echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none;line-height: 11px">' . $r->RoomBill->Registration->MarketSegment->name . '</td>';
                    echo '<td class="print2" style="text-align:center;border-bottom:none;border-top:none;line-height: 11px">' . date('d-m-Y', strtotime($r->RoomBill->Registration->date_from)) . '</td>';
//                    echo '<td style="text-align:left">' . $r->RoomBill->Registration->date_to . '</td>';
                    echo '<td class="print2" style="text-align:center;border-bottom:none;border-top:none;line-height: 11px">' . date('d-m-Y', strtotime($r->RoomBill->maxDate)) . '</td>';
//                    echo '<td style="text-align:right">' . landa()->rp($rate,false) . '</td>';
                    echo '<td class="print2" style="text-align:right;border-bottom:none;border-top:none;line-height: 11px">' . landa()->rp($extrabed, false) . '</td>';
                    echo '<td class="print2" style="text-align:right;border-bottom:none;border-top:none;line-height: 11px">' . $r->RoomBill->pax . '</td>';
                    echo '<td class="print2" style="text-align:right;border-bottom:none;border-top:none;line-height: 11px">' . landa()->rp($charge, false) . '</td>';
                    echo '<td class="print2" style="text-align:right;border-bottom:none;border-top:none;line-height: 11px">' . landa()->rp($bf, false) . '</td>';

                    $other = 0;
                    $txtOther = '';
                    //other include
                    $others_include = json_decode($siteConfig->others_include);
                    foreach ($others_include as $mInclude) {
                        $mOther = json_decode($r->RoomBill->others_include, true);
                        $tuyul = ChargeAdditional::model()->findByPk($mInclude);
                        if ($r->RoomBill->charge == 0) {
                            if ($r->RoomBill->others_include != '') {
                                $txtOther .= $tuyul->name . ' : ' . landa()->rp(0, false) . '<br>';
                            }
                        } else {
                            if ($r->RoomBill->others_include != '') {
                                if (isset($mOther[$mInclude])) {
                                    $tuyul = ChargeAdditional::model()->findByPk($mInclude);
                                    $other += $mOther[$mInclude];
                                    $txtOther .= $tuyul->name . ' : ' . landa()->rp($mOther[$mInclude], false) . '<br>';
//                                $total[$tuyul->id] += $mOther[$mInclude];
                                    $totOther += $mOther[$mInclude];
                                }
                            }
                        }
                    }
                    //package
                    if ($r->RoomBill->package_room_type_id != 0) {
                        $package = json_decode($roomType[$r->RoomBill->package_room_type_id]['charge_additional_ids']);
                        if (!empty($package)) {
                            foreach ($package as $mPackage) {
                                $tuyul = ChargeAdditional::model()->findByPk($mPackage->id);
                                $txtOther .= $tuyul->name . ' : ' . landa()->rp($mPackage->amount * $mPackage->charge, false) . '<br>';
                                $other += $mPackage->amount * $mPackage->charge;
                                $totOther += $mPackage->amount * $mPackage->charge;
                            }
                        }
                    }


                    echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none;line-height: 11px">' . $txtOther . '</td>';
                    echo '<td class="print2" style="text-align:right;border-bottom:none;border-top:none;line-height: 11px">' . landa()->rp($r->RoomBill->charge, false) . '</td>';

//
//                    foreach ($others_include as $mInclude) {
//                        $mOther = json_decode($r->RoomBill->others_include, true);
//                        $other = 0;
//                        if ($r->RoomBill->others_include != '') {
//                            if (isset($mOther[$mInclude])) {
//                                $other = $mOther[$mInclude];
//                                $total[$mInclude] += $mOther[$mInclude];
//                            }
//                        }
//
//                        echo '<td style="text-align:right">' . landa()->rp($other, false) . '</td>';
//                    }

                    echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none;line-height: 11px">' . strip_tags($r->RoomBill->Registration->remarks) . '</td>';
                    echo '</tr>';
//                    $totRoomRate += $rate;
                    $totEB += $extrabed;
                    $totRoomCharge += $charge;
                    $totPax += $r->RoomBill->pax;
                    $totBF +=$bf;
                    $totRoomRate+= $r->RoomBill->charge;
                }
            }
            ?>
            <tr>
                <td class="print2" colspan="7" style="text-align: right">Total :</td>
                <!--<td style="text-align: right"><?php // echo landa()->rp($totRoomRate,false)            ?></td>-->                             
                <td class="print2" style="text-align: right"><?php echo landa()->rp($totEB, false) ?></td>                                         
                <td class="print2" style="text-align: right"><?php echo $totPax ?></td>                             
                <td class="print2" style="text-align: right"><?php echo landa()->rp($totRoomCharge, false) ?></td>                             
                <td class="print2" style="text-align: right"><?php echo landa()->rp($totBF, false) ?></td> 
                <td class="print2" style="text-align: right"><?php echo landa()->rp($totOther, false) ?></td>                             
                <td class="print2" style="text-align: right"><?php echo landa()->rp($totRoomRate, false) ?></td>   
                <?php
//                $others_include = json_decode($siteConfig->others_include);
//                foreach ($others_include as $mInclude) {
//                    $tuyul = ChargeAdditional::model()->findByPk($mInclude);
//                    echo '<td style="text-align: right">' . landa()->rp($total[$tuyul->id], false) . '</td>';
//                }
                ?>
                <td  style="text-align: right"></td>                             
            </tr> 
        </tbody>
    </table>
    <br>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td style="padding: 0px;width: 30%;font-size: 10px" class="span2">Audit By</td>        
                <td style="padding: 0px;font-size: 10px">: <?php echo $model->Cashier->name; ?></td>
            </tr>    
            <tr>
                <td style="padding: 0px;font-size: 10px">Printed Time</td>        
                <td style="padding: 0px;font-size: 10px">: <?php echo date('l d-M-Y H:i:s', strtotime($model->created)); ?></td>
            </tr>  
        </tbody>
    </table>

</div>
<style type="text/css">
   
    @media print
    {
        body {visibility:hidden;}
        .na_inHouse{visibility: visible;} 
        .na_inHouse{width: 100%;top: 5px;left: 5px;position: absolute;font-size: 9px !important}     
         
    }
</style>
