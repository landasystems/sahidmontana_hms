<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_detail_account')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_detail_account" id="na_detail_account" style="text-align: center;width: 100%">
    <center><h4>TRANSACTION BY ACCOUNT</h4></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px;">
     <table class="tbPrint" style="font-size: 10px;line-height: 1px !important;">
        <?php
        $settings = json_decode($siteConfig->settings, true);
        $roomAcc = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
        $breakfastAcc = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';

        $totAll = 0;
        $no = 1;
        $roomBills = array_filter($naDet, function($naDet) {
            return $naDet['room_bill_id'] != 0;
        });
        ?>
        <thead>   
            <tr>
                <th class="span1 print2"  style="text-align: center;vertical-align: middle;border-bottom:solid #000 2px !important;border-top:solid #000 2px">No</th>
                <th class="span5 print2" style="text-align: center;vertical-align: middle;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Remarks / Guest</th>            
                <th class="span2 print2"  style="width:8%;text-align: center;vertical-align: middle;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Room</th>                           
                <th class="span3 print2"  style="width:9%;text-align: center;vertical-align: middle;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Amount</th>            
                <th class="span3 print2"   style="text-align: center;vertical-align: middle;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Price</th>            
                <th class="span3 print2"  style="text-align: center;vertical-align: middle;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Total</th>                            ;
                <th class="span3 print2"  style="text-align: center;vertical-align: middle;border-bottom:solid #000 2px !important;border-top:solid #000 2px">Cashier</th>            
            </tr> 
        </thead>   
        <?php
        foreach ($account as $acc) {
            ?>

            <tr><td class="print2" style="border-bottom:solid #000 2px !important;border-top:solid #000 2px" colspan="7"><b><?php echo strtoupper($acc->name); ?></b></td></tr>


            <?php
            $totalAccount = 0;
            $no = 1;
            $room_id = array();


            //from room charge
            if ($acc->id == $roomAcc) {
                foreach ($roomBills as $data) {
                    if ($data->RoomBill->charge != 0 && $data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->RoomBill->date_bill == $data->Na->date_na && $data->RoomBill->charge != 0)) {
                        $sName = (isset($data->RoomBill->Registration->User->name))?ucwords($data->RoomBill->Registration->User->name) : '';
                        echo '<tr>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $no . '</td>';
                        echo '<td class="print2" style="border-bottom:none;border-top:none">Room Charge</td>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $data->RoomBill->room_number . '</td>';
                        echo '<td class="print2" style="text-align:center;border-bottom:none;border-top:none">1</td>';
                        echo '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($data->RoomBill->room_price, false) . '</td>';
                        echo '<td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($data->RoomBill->room_price, false) . '</td>';
                        echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $sName . '</td>';
                        echo '</tr>';
                        $no++;
                        $totAll += $data->RoomBill->room_price;
                        $totalAccount += $data->RoomBill->room_price;
                    }
                }
            }

            //from extrabed
            if ($acc->id == $roomAcc) {
                foreach ($roomBills as $data) {
                    if ($data->RoomBill->charge != 0 && $data->RoomBill->extrabed > 0 && $data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->RoomBill->date_bill == $data->Na->date_na && $data->RoomBill->charge != 0 && $data->RoomBill->extrabed > 0)) {
                        echo '<tr>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $no . '</td>';
                        echo '<td class="print2" style="border-bottom:none;border-top:none">Extrabed</td>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $data->RoomBill->room_number . '</td>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $data->RoomBill->extrabed . '</td>';
                        echo '<td class="print2" style="text-align: right;border-bottom:none;border-top:none">' . landa()->rp($data->RoomBill->extrabed_price, false) . '</td>';
                        echo '<td class="print2" style="text-align: right;border-bottom:none;border-top:none">' . landa()->rp($data->RoomBill->extrabed * $data->RoomBill->extrabed_price, false) . '</td>';
                        echo '<td class="print2" style="text-align: left;border-bottom:none;border-top:none">' . ucwords($data->RoomBill->Registration->User->name) . '</td>';
                        echo '</tr>';
                        $no++;
                        $totAll += $data->RoomBill->extrabed * $data->RoomBill->extrabed_price;
                        $totalAccount += $data->RoomBill->extrabed * $data->RoomBill->extrabed_price;
                    }
                }
            }

            //from breakfast
            //if ($data->RoomBill->charge != 0 && $data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->RoomBill->date_bill == $data->Na->date_na && $data->RoomBill->charge != 0)) {

            if ($acc->id == $breakfastAcc) {
                foreach ($roomBills as $data) {
//                    if ($data->RoomBill->charge != 0) {
                    if ($data->is_checkedout == 0 && $data->RoomBill->charge != 0 || ($acc->id == $breakfastAcc && $data->is_checkedout == 1 && $data->RoomBill->date_bill == $data->Na->date_na && $data->RoomBill->charge != 0)) {
                        echo '<tr>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $no . '</td>';
                        echo '<td class="print2" style="border-bottom:none;border-top:none">Breakfast</td>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $data->RoomBill->room_number . '</td>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $data->RoomBill->pax . '</td>';
                        echo '<td class="print2" style="text-align: right;border-bottom:none;border-top:none">' . landa()->rp($data->RoomBill->fnb_price, false) . '</td>';
                        echo '<td class="print2" style="text-align: right;border-bottom:none;border-top:none">' . landa()->rp($data->RoomBill->fnb_price * $data->RoomBill->pax, false) . '</td>';
                        echo '<td class="print2" style="text-align: left;border-bottom:none;border-top:none">' . ucwords($data->RoomBill->Registration->User->name) . '</td>';
                        echo '</tr>';
                        $no++;
                        $totAll += $data->RoomBill->fnb_price * $data->RoomBill->pax;
                        $totalAccount += $data->RoomBill->fnb_price * $data->RoomBill->pax;
                    }
                }
            }

            //from paket        
//            $departement_id = '';
//            foreach ($roomBills as $data) {
//                //check package setiap room_id
//                if ($data->RoomBill->package_room_type_id != 0 && $data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->RoomBill->date_bill == $data->Na->date_na && $data->RoomBill->package_room_type_id != 0 )) {
//                    $name = ucwords($data->RoomBill->Package->name);
//                    $package = json_decode($roomType[$data->RoomBill->package_room_type_id]['charge_additional_ids']);
//                    if (!empty($package)) {
//                        foreach ($package as $mPackage) {
//                            $additional = ChargeAdditional::model()->findByPk($mPackage->id);
//                            if ($additional->account_id == $acc->id) {
//                                $price = $mPackage->amount * $additional->charge;
//                                echo '<tr>';
//                                echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $no . '</td>';
//                                echo '<td class="print2" style="border-bottom:none;border-top:none">[' . $name . '] ' . $additional->name . '</td>';
//                                echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $data->RoomBill->room_number . '</td>';
//                                echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $mPackage->amount . '</td>';
//                                echo '<td class="print2" style="text-align: right;border-bottom:none;border-top:none">' . landa()->rp($additional->charge, false) . '</td>';
//                                echo '<td class="print2" style="text-align: right;border-bottom:none;border-top:none">' . landa()->rp($price, false) . '</td>';
//                                echo '<td class="print2" style="text-align: left;border-bottom:none;border-top:none">' . ucwords($data->RoomBill->Registration->User->name) . '</td>';
//                                echo '</tr>';
//                                $no++;
//                                $totAll += $price;
//                                $totalAccount += $price;
//                            }
//                        }
//                    }
//                }
//            }



            foreach ($roomBills as $data) {
                if ($data->RoomBill->charge != 0 && $data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->RoomBill->date_bill == $data->Na->date_na && $data->RoomBill->charge != 0)) {
                    //perulangan untuk other includes
                    if ($data->RoomBill->others_include != '') {
                        $others_include = json_decode($data->RoomBill->others_include);
                        foreach ($others_include as $key => $mInclude) {                            
                            $tuyul = ChargeAdditional::model()->findByPk($key);
                            if ($tuyul->account_id == $acc->id) {
                                echo '<tr>';
                                echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $no . '</td>';
                                echo '<td class="print2" style="border-bottom:none;border-top:none">' . $tuyul->name . '</td>';
                                echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $data->RoomBill->room_number . '</td>';
                                echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">1</td>';
                                echo '<td class="print2" style="text-align: right;border-bottom:none;border-top:none">' . landa()->rp($mInclude, false) . '</td>';
                                echo '<td class="print2" style="text-align: right;border-bottom:none;border-top:none">' . landa()->rp($mInclude, false) . '</td>';
                                echo '<td class="print2" style="text-align: left;border-bottom:none;border-top:none">' . ucwords($data->RoomBill->Registration->User->name) . '</td>';
                                echo '</tr>';
                                $no++;
                                $totAll += $mInclude;
                                $totalAccount += $mInclude;
                            }
                        }
                    }
                }
            }

            $billCharge = array_filter($naDet, function($naDet) {
                return $naDet['bill_charge_id'] != 0;
            });

            //from transaction        
            foreach ($billCharge as $charge) {
                $billChargeDet = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $charge->BillCharge->id . ' and deposite_id=0'));
                foreach ($billChargeDet as $chargeDet) {
                    if ($chargeDet->Additional->account_id == $acc->id && $charge->BillCharge->is_temp == 0) {
                        $r = (isset($charge->RoomBill->room_number)) ? $charge->RoomBill->room_number : '';
                        $roomNumber = ($charge->BillCharge->gl_charge != 0 && !empty($charge->BillCharge->gl_room_bill_id)) ? $r : '-';
                        $sName = (isset($charge->BillCharge->Cashier->name)) ? ucwords($charge->BillCharge->Cashier->name) : '';
                        echo '<tr>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $no . '</td>';
                        echo '<td class="print2" style="border-bottom:none;border-top:none">[' . $charge->BillCharge->code . '] ' . $chargeDet->Additional->name . '</td>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $roomNumber . '</td>';
                        echo '<td class="print2" style="text-align: center;border-bottom:none;border-top:none">' . $chargeDet->amount . '</td>';
                        echo '<td class="print2" style="text-align: right;border-bottom:none;border-top:none">' . landa()->rp($chargeDet->netCharge, false) . '</td>';
                        echo '<td class="print2" style="text-align: right;border-bottom:none;border-top:none">' . landa()->rp($chargeDet->netTotal, false) . '</td>';
                        echo '<td class="print2" style="text-align: left;border-bottom:none;border-top:none">' . $sName . '</td>';
                        echo '</tr>';
                        $no++;
                        $totAll += $chargeDet->netTotal;
                        $totalAccount += $chargeDet->netTotal;
                    }
                }
            }
            ?>
            <tr>
                <td colspan="5" style="text-align: right">Total <?php echo ucwords(strtolower($acc->name)); ?> :</td>                  
                <td style="text-align: right"><?php echo landa()->rp($totalAccount, false); ?></td>                                 
                <td style="text-align: right"></td>            
            </tr> 
            <tr>
                <td colspan="5" style="text-align: right">Net <?php echo ucwords(strtolower($acc->name)); ?> :</td>                  
                <td style="text-align: right"><?php echo landa()->rp($totalAccount / $acc->net, false); ?></td>                                 
                <td style="text-align: right"></td>            
            </tr> 
            <tr ><td  class="print2" style="text-align:right;border-left:none;border-right:none;line-height: 0px;" colspan="7">&nbsp;</td></tr>
            </tbody>

            <?php
        }
        ?>

        <thead>  
            <tr>
                <th colspan="5" style="text-align: right">Total Transaction :</th>
                <th style="text-align: right"><?php echo landa()->rp($totAll, false); ?></th>                             
                <th style="text-align: right"></th>            
            </tr>
            <tr>
                <th colspan="5" style="text-align: right">Net Transaction :</th>
                <th style="text-align: right"><?php echo landa()->rp($totAll / $acc->net, false); ?></th>                             
                <th style="text-align: right"></th>            
            </tr>
        </thead>
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
    .noborder{
        border: 0px !important;
    }
    @media print
    {
        body {visibility:hidden;}
        .na_detail_account{visibility: visible;} 
        .na_detail_account{width: 100%;top: 0px;left: 0px;position: absolute;} 

       table.tbPrint2 td, table.tbPrint2 th {
            border: solid #000 2px;
        }
        .tbPrint2 td{
            background: #e8edff; 
            border-bottom: none ;
            border-left: none;
            border-right: none;
            color: #669;
            border-top: none;
        }
    }


</style>
