<?php
$siteConfig = SiteConfig::model()->findByPk(1);
$settings = json_decode($siteConfig->settings, true);
$roomAcc = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
$breakfastAcc = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';
$roomType = RoomType::model()->findAll(array('index' => 'id'));

$detail = '';
$total = 0;
$id = 1;
$type_transaction = SiteConfig::model()->getStandartTransactionMalang();

foreach ($naDet as $na) {
    if ($na->room_bill_id != 0) {
        if ($na->RoomBill->charge != 0) {
            $roomPrice = $na->RoomBill->room_price;
            //kurangi roomprice dengan paket jika jenisroom adalah paket
//            if ($na->RoomBill->package_room_type_id != 0) {
//                $package = json_decode($roomType[$na->RoomBill->package_room_type_id]['charge_additional_ids']);
//                if (!empty($package)) {
//                    foreach ($package as $mPackage) {
//                        $additional = ChargeAdditional::model()->findByPk($mPackage->id);
//                        $roomPrice -= $mPackage->amount * $additional->charge;
//                    }
//                }
//            }
            //check fnb jika 1 orang, maka sisa dikasihkan room
            $fb = $na->RoomBill->pax * $na->RoomBill->fnb_price;
//            if ($na->RoomBill->pax == 1) {
//                $roomPrice += $na->RoomBill->pax * $na->RoomBill->fnb_price;
//            }

            $noID = '0000000' . $id;
            $hasil =  ($roomPrice + ($na->RoomBill->extrabed * $na->RoomBill->extrabed_price));
            $detail .= '<tr class="items">
                        <td style="text-align:center">TRX' . substr($noID, strlen($noID) - 5, 5) . '</td>                                                    
                        <td style="text-align:left">Room Charge + Extrabed</td>                                                    
                        <td style="text-align:center">' . $na->RoomBill->Registration->code . '</td>                                                    
                        <td style="text-align:center">' . 'ATS' . '</td>                                                    
                        <td style="text-align:center">' . date('Y-m-d', strtotime($date)) . '</td>                                                    
                        <td style="text-align:left">' . $type_transaction['ATS'] . '</td>                                                    
                        <td style="text-align:right">' . landa()->rp($hasil) . '</td>                                                    
                        <td style="text-align:center">Yes</td>                                                                            
                    </tr>';
            $total+= $hasil;
            $id++;

            if ($na->RoomBill->package_room_type_id != 0) {
                $package = json_decode($roomType[$na->RoomBill->package_room_type_id]['charge_additional_ids']);
                if (!empty($package)) {
                    foreach ($package as $mPackage) {
                        $additional = ChargeAdditional::model()->findByPk($mPackage->id);
                        $noID = '0000000' . $id;
                        $hasil = ($mPackage->amount * $mPackage->charge);                        
                        $detail .= '<tr class="items">
                                        <td style="text-align:center">TRX' . substr($noID, strlen($noID) - 5, 5) . '</td>                                                    
                                        <td style="text-align:left">[Room Package] ' . $additional->name . '</td> 
                                        <td style="text-align:center">' . $na->RoomBill->Registration->code . '</td>                                                    
                                        <td style="text-align:center">' . $additional->type_transaction . '</td>                                                    
                                        <td style="text-align:center">' . date('Y-m-d', strtotime($date)) . '</td>                                                    
                                        <td style="text-align:left">' . $type_transaction[$additional->type_transaction] . '</td>                                                    
                                        <td style="text-align:right">' . landa()->rp($hasil) . '</td>                                                    
                                        <td style="text-align:center">Yes</td>                                                                            
                                    </tr>';
                        $total+= $hasil;
                        $id++;
                    }
                }
            }

            if ($na->RoomBill->others_include != '') {
                $others_include = json_decode($na->RoomBill->others_include);
                foreach ($others_include as $key => $mInclude) {
                    $additional = ChargeAdditional::model()->findByPk($key);
                    $noID = '0000000' . $id;
                    $hasil = $mInclude;
                    $detail .= '<tr class="items">
                                        <td style="text-align:center">TRX' . substr($noID, strlen($noID) - 5, 5) . '</td>                                                    
                                        <td style="text-align:left">[Other Include] ' . $additional->name . '</td> 
                                        <td style="text-align:center">' . $na->RoomBill->Registration->code . '</td>                                                    
                                        <td style="text-align:center">' . $additional->type_transaction . '</td>                                                    
                                        <td style="text-align:center">' . date('Y-m-d', strtotime($date)) . '</td>                                                    
                                        <td style="text-align:left">' . $type_transaction[$additional->type_transaction] . '</td>                                                    
                                        <td style="text-align:right">' . landa()->rp($hasil) . '</td>                                                    
                                        <td style="text-align:center">Yes</td>                                                                            
                                    </tr>';
                    $total+= $hasil;
                    $id++;
                }
            }
            //breakfast
            $noID = '0000000' . $id;
            $hasil = $fb;
            $detail .= '<tr class="items">
                                        <td style="text-align:center">TRX' . substr($noID, strlen($noID) - 5, 5) . '</td>                                                    
                                        <td style="text-align:left">Breakfast</td> 
                                        <td style="text-align:center">' . $na->RoomBill->Registration->code . '</td>                                                    
                                        <td style="text-align:center">ATM</td>                                                    
                                        <td style="text-align:center">' . date('Y-m-d', strtotime($date)) . '</td>                                                    
                                        <td style="text-align:left">' . $type_transaction['ATM'] . '</td>                                                    
                                        <td style="text-align:right">' . landa()->rp($hasil) . '</td>                                                    
                                        <td style="text-align:center">Yes</td>                                                                            
                                    </tr>';
            $total+= $hasil;
            $id++;
        }
    }

    if ($na->bill_charge_id != 0) {
        $billChargeDets = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $na->bill_charge_id));
        foreach ($billChargeDets as $billDet) {
            $noID = '0000000' . $id;
            $hasil = (($billDet->charge * $billDet->amount) - (($billDet->discount / 100) * ($billDet->charge * $billDet->amount)));
            $detail .= '<tr class="items">
                        <td style="text-align:center">TRX' . substr($noID, strlen($noID) - 5, 5) . '</td>   
                        <td style="text-align:left">' . $billDet->Additional->name . '</td> 
                        <td style="text-align:center">' . $billDet->BillCharge->code . '</td>                                                    
                        <td style="text-align:center">' . $billDet->Additional->type_transaction . '</td>                                                    
                        <td style="text-align:center">' . date('Y-m-d', strtotime($date)) . '</td>                                                    
                        <td style="text-align:left">' . $type_transaction[$billDet->Additional->type_transaction] . '</td>                                                    
                        <td style="text-align:right">' . landa()->rp($hasil) . '</td>                                                    
                        <td style="text-align:center">Yes</td>                                                    
                        
                    </tr>';
            $id++;
            $total+= $hasil;
        }
    }
}
?>
<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="3"><h2>TAX EXPORT</h2>
            <?php // echo date('d/m/Y', strtotime($date[0])) . " - " . $date_to = date('d/m/Y', strtotime($date[1]));   ?>
            <hr></td>
    </tr>
</table>
<div class="row-fluid">

    <table class="responsive table " width="100%">
        <thead>

            <tr>                
                <th class="span1" style="text-align: center">ID</th>                
                <th class="span2" style="text-align: center">NAME</th>                
                <th class="span2" style="text-align: center">Nota</th>
                <th class="span2" class="span1" style="text-align: center">Code Transaction</th>
                <th class="span2" style="text-align: center">Date</th>
                <th class="span3" style="text-align: center">Description</th>
                <th class="span2" style="text-align: center">Total</th>
                <th class="span1" style="text-align: center">Tax</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo $detail;
            ?>
            <tr>                
                <th colspan="6" style="text-align: right"><b>Grand Total : </b></th>               
                <th colspan="2" style="text-align: right"><?php echo landa()->rp($total); ?></th>
            </tr>
        </tbody>

    </table>

</div>
</div>