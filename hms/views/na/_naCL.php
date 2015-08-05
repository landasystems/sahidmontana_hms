<center><h3>DETAIL CITY LEDGER</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>

<table class="items table table-striped table-hover  table-condensed">
    <thead>
        <tr>
            <th class="span1" style="text-align: center">NO</th>            
            <th class="span2" style="text-align: center">TRANS TYPE</th>            
            <th class="span2" style="text-align: center">TRANS CODE</th>                        
            <th class="span2" style="text-align: center">GUEST NAME</th>                        
            <th class="span2" style="text-align: center">AMOUNT</th>                                    
            <th class="span2" style="text-align: center">CASHIER</th>            
        </tr>                        
    </thead>
    <tbody>
        <?php
        $totCL = 0;
        $no = 1;
        $user = User::model()->listUser();
        foreach ($bill as $b) {
            if ($b->ca_charge != 0 and !empty($b->ca_user_id)) {
                echo '<tr>';
                echo '<td style="text-align:center">' . $no . '</td>';
                echo '<td>General Payment</td>';
                echo '<td>' . $b->code . '</td>';
                echo '<td>' . $user[$b->ca_user_id]['name'] . '</td>';
                echo '<td>' . landa()->rp($b->ca_charge) . '</td>';
                echo '<td>' . ucwords($b->Cashier->name) . '</td>';
                echo '</tr>';
                $no++;
                $totCL += $b->ca_charge;
            }
        }

        foreach ($billCharge as $b) {
            if ($b->ca_charge != 0 and !empty($b->ca_user_id)) {
                $billChargeId = $b->id;
                $filterBillChargeDet = array_filter($billChargeDet, function($billChargeDet) use($billChargeId) {
                    return $billChargeDet['bill_charge_id'] == $billChargeId;
                });

                foreach ($filterBillChargeDet as $key => $value) {
                    $name = getRootName($value['charge_additional_id'], $additional, $category, $category_all);
                }

                echo '<tr>';
                echo '<td  style="text-align:center">' . $no . '</td>';
                echo '<td>' . $name . '</td>';                
                echo '<td>' . $b->code . '</td>';
                echo '<td>' . $user[$b->ca_user_id]['name'] . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($b->ca_charge) . '</td>';
                echo '<td>' . ucwords($b->Cashier->name) . '</td>';
                echo '</tr>';
                $no++;
                $totCL += $b->ca_charge;
            }
        }               
        ?>     
        <tr><td colspan="6">&nbsp;</td></tr>
        <tr>
            <td colspan="4" style="text-align: right">Total City Ledger Today :</td>
            <td style="text-align: right"><?php echo landa()->rp($totCL) ?></td>                               
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