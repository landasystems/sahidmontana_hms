<center><h3>TRANSACTION BY ACCOUNT</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>
<table class="items table table-striped  table-condensed">
    <?php
    $settings = json_decode($siteConfig->settings, true);
    $roomAcc = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
    $breakfastAcc = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';

    $totAll = 0;
    $totNetAll = 0;
    $no = 1;
    ?>

    <?php
    foreach ($account as $acc) {
        ?>
        <thead>            
            <tr><th colspan="7"><?php echo strtoupper($acc->name); ?></th></tr>
            <tr>
                <th class="span1"  style="text-align: center;vertical-align: middle">No</th>
                <th class="span5" style="text-align: center;vertical-align: middle">Remarks / Guest</th>            
                <th class="span2"  style="width:8%;text-align: center;vertical-align: middle">Room</th>                           
                <th class="span3"  style="width:9%;text-align: center;vertical-align: middle">Amount</th>            
                <th class="span3"   style="text-align: center;vertical-align: middle">Price</th>            
                <th class="span3"  style="text-align: center;vertical-align: middle">Total</th>                            
                <th class="span3"  style="text-align: center;vertical-align: middle">Cashier</th>            
            </tr>                             
        </thead>
        <?php
        $totalAccount = 0;
        $no = 1;
        $room_id = array();


        //from room charge
        if ($acc->id == $roomAcc) {
            foreach ($roomBills as $data) {
                if ($data->is_checkedout == 0 && $data->is_na == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system && $data->is_na == 0)) {
                    echo '<tr>';
                    echo '<td style="text-align: center">' . $no . '</td>';
                    echo '<td>Room Charge</td>';
                    echo '<td style="text-align: center">' . $data->room_number . '</td>';
                    echo '<td style="text-align:center">1</td>';
                    echo '<td style="text-align:right">' . landa()->rp($data->room_price, false) . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($data->room_price, false) . '</td>';
                    echo '<td >' . ucwords($data->Registration->User->name) . '</td>';
                    echo '</tr>';
                    $no++;
                    $totAll += $data->room_price;
                    $totNetAll += $data->room_price / $acc->net;
                    $totalAccount += $data->room_price;
                }
            }
        }

        //from extrabed
        if ($acc->id == $roomAcc) {
            foreach ($roomBills as $data) {
                if ($data->is_checkedout == 0 && $data->is_na == 0 && $data->extrabed > 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system && $data->is_na == 0 && $data->extrabed > 0)) {
                    echo '<tr>';
                    echo '<td style="text-align: center">' . $no . '</td>';
                    echo '<td>Extrabed</td>';
                    echo '<td style="text-align: center">' . $data->room_number . '</td>';
                    echo '<td style="text-align:center">' . $data->extrabed . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($data->extrabed_price, false) . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($data->extrabed * $data->extrabed_price, false) . '</td>';
                    echo '<td >' . ucwords($data->Registration->User->name) . '</td>';
                    echo '</tr>';
                    $no++;
                    $totAll += $data->extrabed * $data->extrabed_price;
                    $totNetAll += ($data->extrabed * $data->extrabed_price ) / $acc->net;
                    $totalAccount += $data->extrabed * $data->extrabed_price;
                }
            }
        }

        //from breakfast
        if ($acc->id == $breakfastAcc) {
            foreach ($roomBills as $data) {
                if ($data->is_na == 0 && $data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system && $data->is_na == 0 )) {
                    echo '<tr>';
                    echo '<td style="text-align: center">' . $no . '</td>';
                    echo '<td>Breakfast</td>';
                    echo '<td style="text-align: center">' . $data->room_number . '</td>';
                    echo '<td style="text-align:center">' . $data->pax . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($data->fnb_price, false) . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($data->fnb_price * $data->pax, false) . '</td>';
                    echo '<td >' . ucwords($data->Registration->User->name) . '</td>';
                    echo '</tr>';
                    $no++;
                    $totAll += $data->fnb_price * $data->pax;
                    $totNetAll += ($data->fnb_price * $data->pax) / $acc->net;
                    $totalAccount += $data->fnb_price * $data->pax;
                }
            }
        }

        //from paket        
//        $departement_id = '';
//        foreach ($roomBills as $data) {
//            //check package setiap room_id
//            if ($data->package_room_type_id != 0 && $data->is_na == 0) {
//                $name = ucwords($data->Package->name);
//                $package = json_decode($roomType[$data->package_room_type_id]['charge_additional_ids']);
//                if (!empty($package)) {
//                    foreach ($package as $mPackage) {
//                        $additional = ChargeAdditional::model()->findByPk($mPackage->id);
//                        if ($additional->account_id == $acc->id) {
//                            $price = $mPackage->amount * $additional->charge;
//                            echo '<tr>';
//                            echo '<td style="text-align: center">' . $no . '</td>';
//                            echo '<td>[' . $name . '] ' . $additional->name . '</td>';
//                            echo '<td style="text-align: center">' . $data->room_number . '</td>';
//                            echo '<td style="text-align:center">' . $mPackage->amount . '</td>';
//                            echo '<td style="text-align:right">' . landa()->rp($additional->charge, false) . '</td>';
//                            echo '<td style="text-align:right">' . landa()->rp($price, false) . '</td>';
//                            echo '<td>' . ucwords($data->Registration->User->name) . '</td>';
//                            echo '</tr>';
//                            $no++;
//                            $totAll += $price;
//                            $totNetAll += $price / $acc->net;
//                            $totalAccount += $price;
//                        }
//                    }
//                }
//            }
//        }



        foreach ($roomBills as $data) {
            if ($data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system)) {
                //perulangan untuk other includes
                if ($data->others_include != '' && $data->is_na == 0) {
                    $others_include = json_decode($data->others_include);
                    foreach ($others_include as $key => $mInclude) {
                        $tuyul = ChargeAdditional::model()->findByPk($key);
                        if ($tuyul->account_id == $acc->id) {
                            echo '<tr>';
                            echo '<td style="text-align: center">' . $no . '</td>';
                            echo '<td>' . $tuyul->name . '</td>';
                            echo '<td style="text-align: center">' . $data->room_number . '</td>';
                            echo '<td style="text-align:center">1</td>';
                            echo '<td style="text-align:right">' . landa()->rp($mInclude, false) . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp($mInclude, false) . '</td>';
                            echo '<td>' . ucwords($data->Registration->User->name) . '</td>';
                            echo '</tr>';
                            $no++;
                            $totAll += $mInclude;
                            $totNetAll += $mInclude / $acc->net;
                            $totalAccount += $mInclude;
                        }
                    }
                }
            }
        }

        //from transaction        
        foreach ($billCharge as $charge) {
            $billChargeDet = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $charge->id . ' and deposite_id=0'));
            foreach ($billChargeDet as $chargeDet) {
                if ($chargeDet->Additional->account_id == $acc->id && $chargeDet->BillCharge->is_temp == 0) {
                    $r = (isset($charge->RoomBill->room_number)) ? $charge->RoomBill->room_number : '';
                    $roomNumber = ($charge->gl_charge != 0 && !empty($charge->gl_room_bill_id)) ? $r : '-';
                    echo '<tr>';
                    echo '<td style="text-align: center">' . $no . '</td>';
                    echo '<td>[' . $charge->code . '] ' . $chargeDet->Additional->name . '</td>';
                    echo '<td style="text-align: center">' . $roomNumber . '</td>';
                    echo '<td style="text-align:center">' . $chargeDet->amount . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($chargeDet->netCharge, false) . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($chargeDet->netTotal, false) . '</td>';
                    echo '<td>' . ucwords($charge->Cashier->name) . '</td>';
                    echo '</tr>';
                    $no++;
                    $totAll += $chargeDet->netTotal;
                    $totNetAll += $chargeDet->netTotal / $acc->net;
                    $totalAccount += $chargeDet->netTotal;
                }
            }
        }
        ?>
        <tr>
            <td colspan="5" style="text-align: right">Gross <?php echo ucwords(strtolower($acc->name)); ?> :</td>                  
            <td style="text-align: right"><?php echo landa()->rp($totalAccount, false); ?></td>                                 
            <td style="text-align: right"></td>            
        </tr> 
        <tr>
            <td colspan="5" style="text-align: right">Net <?php echo ucwords(strtolower($acc->name)); ?> :</td>                  
            <td style="text-align: right"><?php echo landa()->rp($totalAccount / $acc->net, false); ?></td>                                 
            <td style="text-align: right"></td>            
        </tr> 
        <tr style="background:khaki"><td  style="background:khaki" colspan="7">&nbsp;</td></tr>
    </tbody>

    <?php
}
?>

<thead>  
    <tr>
        <th colspan="5" style="text-align: right">Gross Transaction :</th>
        <th style="text-align: right"><?php echo landa()->rp($totAll, false); ?></th>                             
        <th style="text-align: right"></th>            
    </tr>
    <tr>
        <th colspan="5" style="text-align: right">Net Transaction :</th>
        <th style="text-align: right"><?php echo landa()->rp($totNetAll, false); ?></th>                             
        <th style="text-align: right"></th>            
    </tr>
</thead>
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
