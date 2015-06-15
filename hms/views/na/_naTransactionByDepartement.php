<center><h3>TRANSACTION BY OUTLET</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>
<table class="items table table-striped table-bordered table-condensed">
    <?php
    $settings = json_decode($siteConfig->settings, true);
    $roomAcc = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
    $breakfastAcc = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';
    $breakfastAcc = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';

    $totAll = 0;
    $totAllCount = 0;
    $totAllCharge = 0;
    $no = 1;
    $bfOutlet = 2;
    ?>
    <thead>   
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
    <tbody>
        <tr><td colspan="7"><b>ROOM</b></td></tr>
        <?php
        $subTot = 0;
        $subTotCount = 0;
        $subTotCharge = 0;
        foreach ($roomBills as $data) {
            if ($data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system)) {
                echo '<tr>';
                echo '<td style="text-align: left">' . $no . '</td>';
                echo '<td>Room Charge</td>';
                echo '<td style="text-align: center">' . $data->room_number . '</td>';
                echo '<td style="text-align:center">1</td>';
                echo '<td style="text-align:right">' . landa()->rp($data->room_price, false) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($data->room_price, false) . '</td>';
                echo '<td >' . ucwords($data->Registration->User->name) . '</td>';
                echo '</tr>';
                $no++;
                $subTot += $data->room_price;
                $subTotCount++;
                $subTotCharge += $data->room_price;
            }
        }


        $totAll += $subTot;
        $totAllCount += $subTotCount;
        $totAllCharge += $subTotCharge;
        ?>
        <tr>
            <td colspan="3" style="text-align: right">Total Room Charge :</td>                           
            <td style="text-align: center"><?php echo landa()->rp($subTotCount, false); ?></td>            
            <td style="text-align: right"><?php echo landa()->rp($subTotCharge, false); ?></td>            
            <td style="text-align: right"><?php echo landa()->rp($subTot, false); ?></td>            
            <td style="text-align: right"></td>            
        </tr> 
        <tr><td   style="background:khaki" colspan="7">&nbsp;</td></tr>
    </tbody>

    <?php
    $no = 1;
    $subTot = 0;
    $subTotCount = 0;
    $subTotCharge = 0;
    ?>
    <thead>            
        <tr><th colspan="7">EXTRABED</th></tr>                                 
    </thead>
    <tbody>
    <tbody>
        <?php
        foreach ($roomBills as $data) {
            if ($data->extrabed > 0 && $data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system && $data->extrabed > 0)) {
                echo '<tr>';
                echo '<td style="text-align: left">' . $no . '</td>';
                echo '<td>Extrabed</td>';
                echo '<td style="text-align: left">' . $data->room_number . '</td>';
                echo '<td style="text-align:center">' . $data->extrabed . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($data->extrabed_price, false) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($data->extrabed * $data->extrabed_price, false) . '</td>';
                echo '<td >' . ucwords($data->Registration->User->name) . '</td>';
                echo '</tr>';
                $no++;
                $subTot += $data->extrabed * $data->extrabed_price;
                $subTotCount += $data->extrabed;
                $subTotCharge += $data->extrabed_price;
            }
        }
        $totAll += $subTot;
        $totAllCount += $subTotCount;
        $totAllCharge += $subTotCharge;
        ?>
        <tr>
            <td colspan="3" style="text-align: right">Total Extrabed :</td>
            <td style="text-align: center"><?php echo landa()->rp($subTotCount, false); ?></td>            
            <td style="text-align: right"><?php echo landa()->rp($subTotCharge, false); ?></td>            
            <td style="text-align: right"><?php echo landa()->rp($subTot, false); ?></td>            
            <td style="text-align: right"></td>            
        </tr> 
        <tr><td style="background:khaki" colspan="7">&nbsp;</td></tr>
    </tbody>



    <?php
    foreach ($category as $departement) {
        ?>        
        <tr><td colspan="7"><b><?php echo strtoupper($departement->name); ?></b></td></tr>
        <?php
        $subTot = 0;
        $subTotCount = 0;
        $subTotCharge = 0;
        $no = 1;

        //-------------Retrieve Breakfast---------------------------
        if ($departement->id == $bfOutlet) {
            foreach ($roomBills as $data) {
                if ($data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system && $data->charge != 0)) {
                    echo '<tr>';
                    echo '<td style="text-align: left">' . $no . '</td>';
                    echo '<td>Breakfast</td>';
                    echo '<td style="text-align: left">' . $data->room_number . '</td>';
                    echo '<td style="text-align:center">' . $data->pax . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($data->fnb_price, false) . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($data->fnb_price * $data->pax, false) . '</td>';
                    echo '<td >' . ucwords($data->Registration->User->name) . '</td>';
                    echo '</tr>';
                    $no++;
                    $subTot += $data->fnb_price * $data->pax;
                    $subTotCount += $data->pax;
                    $subTotCharge += $data->fnb_price;
                }
            }
            //---------------------_End Retrieve breakfast
        }
        
        //from paket        
//        $departement_id = '';
//        foreach ($roomBills as $data) {
//            //check package setiap room_id
//            if ($data->package_room_type_id != 0 && $data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system && $data->package_room_type_id != 0 )) {
//                $name = ucwords($data->Package->name);
//                $package = json_decode($roomType[$data->package_room_type_id]['charge_additional_ids']);
//                if (!empty($package)) {
//                    foreach ($package as $mPackage) {
//                        $additional = ChargeAdditional::model()->findByPk($mPackage->id);
//                        if ($additional->charge_additional_category_id == $departement->id) {
//                            $price = $mPackage->amount * $mPackage->charge;
//                            echo '<tr>';
//                            echo '<td style="text-align: center">' . $no . '</td>';
//                            echo '<td>[' . $name . '] ' . $additional->name . '</td>';
//                            echo '<td style="text-align: center">' . $data->room_number . '</td>';
//                            echo '<td style="text-align:center">' . $mPackage->amount . '</td>';
//                            echo '<td style="text-align:right">' . landa()->rp($mPackage->charge, false) . '</td>';
//                            echo '<td style="text-align:right">' . landa()->rp($price, false) . '</td>';
//                            echo '<td>' . ucwords($data->Registration->User->name) . '</td>';
//                            echo '</tr>';
//                            $no++;
//                            $subTot += $price;
//                            $subTotCount += $mPackage->amount;
//                            $subTotCharge += $mPackage->charge;
//                        }
//                    }
//                }
//            }
//        }


        //other includes
        foreach ($roomBills as $data) {
            if ($data->is_checkedout == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system && $data->charge != 0)) {
                //perulangan untuk other includes
                if ($data->others_include != '') {
                    $others_include = json_decode($data->others_include);
                    foreach ($others_include as $key => $mInclude) {
                        $tuyul = ChargeAdditional::model()->findByPk($key);
                        if ($tuyul->charge_additional_category_id == $departement->id) {
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
                            $subTot += $mInclude;
                            $subTotCount++;
                            $subTotCharge += $mInclude;
                        }
                    }
                }
            }
        }

        //from transaction        
        foreach ($billCharge as $charge) {
            $billChargeDet = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $charge->id . ' and deposite_id=0'));
            foreach ($billChargeDet as $chargeDet) {
                if ($chargeDet->Additional->charge_additional_category_id == $departement->id && $charge->is_temp == 0) {
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
                    $subTot += $chargeDet->netTotal;
                    $subTotCount +=$chargeDet->amount;
                    $subTotCharge += $chargeDet->netCharge;
                }
            }
        }
        ?>
        <tr>
            <td colspan="3" style="text-align: right">Total <?php echo ucwords(strtolower($departement->name)); ?> :</td>                  
            <td style="text-align: center"><?php echo landa()->rp($subTotCount, false); ?></td>                                
            <td style="text-align: right"><?php echo landa()->rp($subTotCharge, false); ?></td>                                
            <td style="text-align: right"><?php echo landa()->rp($subTot, false); ?></td>                                 
            <td style="text-align: right"></td>            
        </tr> 
        <tr style="background:khaki"><td  style="background:khaki" colspan="7">&nbsp;</td></tr>
    </tbody>

    <?php
    $totAll += $subTot;
    $totAllCount += $subTotCount;
    $totAllCharge += $subTotCharge;
}
?>

<thead>  
    <tr>
        <th colspan="3" style="text-align: right">Total Transaction :</th>
        <th style="text-align: center"><?php echo landa()->rp($totAllCount, false); ?></th>                             
        <th style="text-align: right"><?php echo landa()->rp($totAllCharge, false); ?></th>                             
        <th style="text-align: right"><?php echo landa()->rp($totAll, false); ?></th>                             
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
