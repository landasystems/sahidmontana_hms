<center><h3>GUEST LEDGER BALANCE</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>
<table class="items table table-striped table-hover  table-condensed">
    <thead>
        <tr>
            <th class="span1" style="text-align: center">NO</th>
            <th class="span2" style="text-align: center">ROOM NUMBER</th>
            <th class="span3" style="text-align: center">GUEST NAME</th>            
            <th class="span2" style="text-align: center">COMPANY</th>            
            <th class="span2" style="text-align: center">PREVIOUS</th>            
            <th class="span2" style="text-align: center">TOTAL REVENUE</th>                     
            <th class="span2" style="text-align: center">DEPOSITE</th>                     
            <th class="span2" style="text-align: center">TUNAI</th>                     
            <th class="span2" style="text-align: center">CREDIT CARD</th>                     
            <th class="span2" style="text-align: center">CITY LEDGER</th>                     
            <th class="span2" style="text-align: center">REFUND</th>                     
            <th class="span2" style="text-align: center">GUEST LEDGER BALANCE</th>            
        </tr>                        
    </thead>
    <tbody>
        <?php
        $no = 1;
        $totPrev = 0;
        $totCharge = 0;
        $totBalance = 0;
        $totTunai = 0;
        $totCreditCard = 0;
        $totDeposite = 0;
        $totCityLedger = 0;
        $totRefund = 0;
        $results = array();
        $tempGuestUserId = 0;
        //---------------------------- GUES LEDGER THAT NOT CHECKOUT ----------------------------//
        foreach ($roomBills_By_Registration as $r) {
            $type = '';
            $thisPrev = 0;
            $thisCharge = 0;
            $thisBalance = 0;
            $thisTunai = 0;
            $thisCreditCard = 0;
            $thisDeposite = 0;
            $thisCityLedger = 0;
            $thisRefund = 0;
            $regId = $r->registration_id;

            //charge            
            $roomBill = RoomBill::model()->findAll(array('condition' => 'registration_id=' . $regId . ' and is_checkedout=0'));
            foreach ($roomBill as $rb) {
                $balance = 0;
//                $thisPackage = 0;
                //roomcharge                    
                if ($rb->charge != 0) {
//                    if ($rb->package_room_type_id != 0) {
//                        $package = json_decode($roomType[$rb->package_room_type_id]['charge_additional_ids']);
//                        if (!empty($package)) {
//                            foreach ($package as $mPackage) {
//                                $thisPackage += $mPackage->total;
//                            }
//                        }
//                    }
//                    $balance = $rb->charge + $thisPackage;
                    $balance = $rb->charge;
                }

                //transaction outlet
                $roomBillId = $rb->id;
                $fBillCharge = BillCharge::model()->findAll(array('condition' => 'gl_room_bill_id=' . $roomBillId));
                foreach ($fBillCharge as $det) {
//                    $cekRoomBill = RoomBill::model()->findAll(array('condition' => 'date_bill ="' . date('Y-m-d', strtotime($det->created)) . '" and registration_id=' . $rb->registration_id . ' and room_id=' . $rb->room_id . ' and is_checkedout=0', 'order' => 'id'));
//                    if (date('Y-m-d', strtotime($det->created)) == $siteConfig->date_system || empty($cekRoomBill)) {
                    if ($det->is_na == 0) {
                        $thisCharge += $det->gl_charge;
                    } else {
                        $thisPrev += $det->gl_charge;
                    }
                }

                if ($r->registration_id == $rb->registration_id && $rb->date_bill == $siteConfig->date_system) {
                    $thisCharge += $balance;
                } elseif ($r->registration_id == $rb->registration_id && $rb->date_bill < $siteConfig->date_system) {
                    $thisPrev += $balance;
                }

                //pengecekan untuk move room, maka bill2 yang di room move sebelumnya di hitung juga
                if ($rb->lead_room_bill_id == 0 && !empty($rb->moved_room_bill_id)) {
                    $arrRoomBill = RoomBill::model()->findAll(array('condition' => 'id IN (' . implode(',', json_decode($rb->moved_room_bill_id)) . ') OR lead_room_bill_id IN (' . implode(',', json_decode($rb->moved_room_bill_id)) . ')'));
                    foreach ($arrRoomBill as $arr) {
                        $thisPrev += $arr->charge;
                    }
                    $fBillCharge = BillCharge::model()->findAll(array('condition' => 'gl_room_bill_id IN (' . implode(',', json_decode($rb->moved_room_bill_id)) . ')'));
                    foreach ($fBillCharge as $det) {
                        $thisPrev += $det->gl_charge;
                    }
                }
            }
            //from dp yang belum chekedout                 
            foreach ($deposite_unused as $d) {
                if ($d->guest_user_id == $r->Registration->guest_user_id) {
                    if ($tempGuestUserId == $r->Registration->Guest->id) //jika user sama, depositenya di nol kan lagi
                        $thisDeposite = 0;
                    else
                        $thisDeposite += $d->balance_amount;
                }
            }
            $thisBalance = $thisPrev + $thisCharge;
            $thisBalance = $thisBalance - $thisDeposite;
            $totPrev += $thisPrev;
            $totCharge += $thisCharge;
            $totBalance += $thisBalance;
            $totDeposite += $thisDeposite;

            $company = $r->Registration->Guest->company;
            /* if (isset($r->Registration->Guest->others)) {
              $json_user = json_decode($r->Registration->Guest->others, true);
              $company = strtoupper($json_user['company']);
              } */


            //cek jika sama user guestnya, maka tr nya di gabung
            if ($tempGuestUserId == $r->Registration->Guest->id) {
                $results[$r->Registration->Guest->id]['roomNumberNotCheckedout'] .= ', ' . $r->roomNumberNotCheckedout;
                $results[$r->Registration->Guest->id]['prev'] += $thisPrev;
                $results[$r->Registration->Guest->id]['charge'] += $thisCharge;
                $results[$r->Registration->Guest->id]['balance'] += $thisBalance;
                $results[$r->Registration->Guest->id]['tunai'] += $thisTunai;
                $results[$r->Registration->Guest->id]['creditcard'] += $thisCreditCard;
                $results[$r->Registration->Guest->id]['cityledger'] += $thisCityLedger;
                $results[$r->Registration->Guest->id]['refund'] += $thisRefund;
//                $results[$r->Registration->Guest->id]['deposite'] += $thisDeposite ;
            } else {
                $results[$r->Registration->Guest->id]['registration_id'] = $r->registration_id;
                $results[$r->Registration->Guest->id]['roomNumberNotCheckedout'] = $r->roomNumberNotCheckedout;
                $results[$r->Registration->Guest->id]['guest_user_id'] = $r->Registration->Guest->id;
                $results[$r->Registration->Guest->id]['guest_user_name'] = $r->Registration->Guest->guestName . $type;
                $results[$r->Registration->Guest->id]['prev'] = $thisPrev;
                $results[$r->Registration->Guest->id]['charge'] = $thisCharge;
                $results[$r->Registration->Guest->id]['balance'] = $thisBalance;
                $results[$r->Registration->Guest->id]['tunai'] = $thisTunai;
                $results[$r->Registration->Guest->id]['creditcard'] = $thisCreditCard;
                $results[$r->Registration->Guest->id]['cityledger'] = $thisCityLedger;
                $results[$r->Registration->Guest->id]['refund'] = $thisRefund;
                $results[$r->Registration->Guest->id]['deposite'] = $thisDeposite;
                $results[$r->Registration->Guest->id]['company'] = $company;
            }
            $tempGuestUserId = $r->Registration->Guest->id;
        }

//        print_r($results);
        //tampilkan hasil setelah penggabungan
        foreach ($results as $arr) {
            $gl_text = '<input type="hidden" name="NaGl[registration_id][]" value="' . $arr['registration_id'] . '" />';
            $gl_text .= '<input type="hidden" name="NaGl[bill_id][]" value="0" />';
            $gl_text .= '<input type="hidden" name="NaGl[room_number][]" value="' . $arr['roomNumberNotCheckedout'] . '" />';
            $gl_text .= '<input type="hidden" name="NaGl[guest_user_id][]" value="' . $arr['guest_user_id'] . '" />';
            $gl_text .='<input type="hidden" name="NaGl[prev][]" value="' . $arr['prev'] . '" />';
            $gl_text .='<input type="hidden" name="NaGl[charge][]" value="' . $arr['charge'] . '" />';
            $gl_text .='<input type="hidden" name="NaGl[balance][]" value="' . $arr['balance'] . '" />';
            $gl_text .='<input type="hidden" name="NaGl[tunai][]" value="' . $arr['tunai'] . '" />';
            $gl_text .='<input type="hidden" name="NaGl[creditcard][]" value="' . $arr['creditcard'] . '" />';
            $gl_text .='<input type="hidden" name="NaGl[cityledger][]" value="' . $arr['cityledger'] . '" />';
            $gl_text .='<input type="hidden" name="NaGl[refund][]" value="' . $arr['refund'] . '" />';
            $gl_text .='<input type="hidden" name="NaGl[deposite][]" value="' . $arr['deposite'] . '" />';

            echo '<tr>';
            echo '<td style="text-align:center">' . $no . $gl_text . '</td>';
            echo '<td style="text-align:center">' . $arr['roomNumberNotCheckedout'] . '</td>';
            echo '<td style="text-align:left">' . $arr['guest_user_name'] . '</td>';
            echo '<td style="text-align:left">' . $arr['company'] . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($arr['prev'], false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($arr['charge'], false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($arr['deposite'], false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($arr['tunai'], false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($arr['creditcard'], false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($arr['cityledger'], false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($arr['refund'], false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($arr['balance'], false) . '</td>';
            echo '</tr>';
            $no++;
        }

        //---------------------------- GUES LEDGER THAT CHECKOUT ----------------------------//
        foreach ($bill as $r) {
            $type = '';
            $thisPrev = 0;
            $thisCharge = 0;
            $thisBalance = 0;
            $thisTunai = 0;
            $thisCreditCard = 0;
            $thisDeposite = 0;
            $thisCityLedger = 0;
            $thisRefund = 0;

            //charge            
            $billDet = BillDet::model()->findAll(array('condition' => 'bill_id=' . $r->id . ' and room_bill_id != 0'));
            foreach ($billDet as $rb) {
                $balance = 0;
                //roomcharge                    
                if (isset($rb->RoomBill->charge)) {
                    if ($rb->RoomBill->charge != 0) {
                        $balance = $rb->RoomBill->charge;
                    }


                    //transaction outlet
                    $roomBillId = $rb->RoomBill->id;
                    $fBillCharge = BillCharge::model()->findAll(array('condition' => 'gl_room_bill_id=' . $roomBillId));
                    foreach ($fBillCharge as $det) {
//                    $cekRoomBill = RoomBill::model()->findAll(array('condition' => 'date_bill ="' . date('Y-m-d', strtotime($det->created)) . '" and registration_id=' . $rb->RoomBill->registration_id . ' and room_id=' . $rb->RoomBill->room_id . ' and is_checkedout=0', 'order' => 'id'));
//                    if (date('Y-m-d', strtotime($det->created)) == $siteConfig->date_system || empty($cekRoomBill)) {
//                        $thisCharge += $det->gl_charge;
//                    } elseif (date('Y-m-d', strtotime($det->created)) < $siteConfig->date_system) {
//                        $thisPrev += $det->gl_charge;
//                    }
                        if ($det->is_na == 0) {
                            $thisCharge += $det->gl_charge;
                        } else {
                            $thisPrev += $det->gl_charge;
                        }
                    }

                    if ($rb->RoomBill->date_bill == $siteConfig->date_system) {
                        $thisCharge += $balance;
                    } elseif ($rb->RoomBill->date_bill < $siteConfig->date_system) {
                        $thisPrev += $balance;
                    }
                } else {
                    logs('bill_id=' . $r->id . ' and room_bill_id != 0');
                }
            }
            $deposite = BillDet::model()->findAll(array('condition' => 'bill_id=' . $r->id . ' and deposite_amount > 0'));
            foreach ($deposite as $dp) {
                $thisDeposite += $dp->deposite_amount;
            }

            $thisTunai = $r->cash;
            $thisCreditCard = $r->cc_charge;
            $thisCityLedger = $r->ca_charge;
            $thisRefund = $r->refund;

            $thisBalance = $thisPrev + $thisCharge - $thisDeposite - $thisTunai - $thisCreditCard - $thisCityLedger + $thisRefund;
            $totPrev += $thisPrev;
            $totCharge += $thisCharge;
            $totBalance += $thisBalance;
            $totDeposite += $thisDeposite;
            $totTunai += $thisTunai;
            $totCreditCard += $thisCreditCard;
            $totCityLedger += $thisCityLedger;
            $totRefund += $thisRefund;


            $gl_text = '<input type="hidden" name="NaGl[registration_id][]" value="0" />';
            $gl_text .= '<input type="hidden" name="NaGl[bill_id][]" value="' . $r->id . '" />';
            $gl_text .= '<input type="hidden" name="NaGl[room_number][]" value="' . $r->roomNumber . '" />';
            $gl_text .= '<input type="hidden" name="NaGl[guest_user_id][]" value="' . $r->guest_user_id . '" />';
            $gl_text .='<input type="hidden" name="NaGl[prev][]" value="' . $thisPrev . '" />';
            $gl_text .='<input type="hidden" name="NaGl[charge][]" value="' . $thisCharge . '" />';
            $gl_text .='<input type="hidden" name="NaGl[balance][]" value="' . $thisBalance . '" />';
            $gl_text .='<input type="hidden" name="NaGl[tunai][]" value="' . $thisTunai . '" />';
            $gl_text .='<input type="hidden" name="NaGl[creditcard][]" value="' . $thisCreditCard . '" />';
            $gl_text .='<input type="hidden" name="NaGl[cityledger][]" value="' . $thisCityLedger . '" />';
            $gl_text .='<input type="hidden" name="NaGl[refund][]" value="' . $thisRefund . '" />';
            $gl_text .='<input type="hidden" name="NaGl[deposite][]" value="' . $thisDeposite . '" />';

            $company = '';
            $guestType = $r->pax_name;
//            if (isset($r->Guest->others)) {
//                $json_user = json_decode($r->Guest->others, true);
//                $company = strtoupper($json_user['company']);
//                $guestType = $r->Guest->guestName . $type;
//            }

            echo '<tr>';
            echo '<td style="text-align:center">' . $no . $gl_text . '</td>';
            echo '<td style="text-align:center">' . $r->roomNumber . '</td>';
            echo '<td style="text-align:left">' . $guestType . '</td>';
            echo '<td style="text-align:left">' . $company . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($thisPrev, false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($thisCharge, false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($thisDeposite, false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($thisTunai, false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($thisCreditCard, false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($thisCityLedger, false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($thisRefund, false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($thisBalance, false) . '</td>';
            echo '</tr>';
            $no++;
        }
        ?>
        <tr>
            <td colspan="4" style="text-align: right">Grand Total :</td>
            <td style="text-align: right"><?php echo landa()->rp($totPrev, false) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totCharge, false) ?></td>                                         
            <td style="text-align: right"><?php echo landa()->rp($totDeposite, false) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totTunai, false) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totCreditCard, false) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totCityLedger, false) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totRefund, false) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totBalance, false) ?></td>                                         
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