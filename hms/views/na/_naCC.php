<center><h3>DETAIL CREDIT CARD</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>

<table class="items table table-striped table-hover table-bordered table-condensed">
    <thead>
        <tr>
            <th class="span1" style="text-align: center">NO</th>            
            <th class="span2" style="text-align: center">TRANS TYPE</th>            
            <th class="span2" style="text-align: center">TRANS CODE</th>                                   
            <th class="span2" style="text-align: center">NO CREDIT CARD</th>                        
            <th class="span2" style="text-align: center">AMOUNT</th>                                    
            <th class="span2" style="text-align: center">CASHIER</th>            
        </tr>                        
    </thead>
    <tbody>
        <?php
        $totCC = 0;
        $no = 1;
        $user = User::model()->listUser();
        foreach ($bill as $b) {
            if ($b->cc_charge != 0) {
                echo '<tr>';
                echo '<td style="text-align:center">' . $no . '</td>';
                echo '<td>General Payment</td>';
                echo '<td>' . $b->code . '</td>';
                echo '<td>' . $b->cc_number . '</td>';
                echo '<td>' . landa()->rp($b->cc_charge) . '</td>';
                echo '<td>' . ucwords($b->Cashier->name) . '</td>';
                echo '</tr>';
                $no++;
                $totCC += $b->cc_charge;
            }
        }

        foreach ($billCharge as $b) {
            if ($b->cc_charge != 0) {
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
                echo '<td>' . $b->cc_number . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($b->ca_charge) . '</td>';
                echo '<td>' . ucwords($b->Cashier->name) . '</td>';
                echo '</tr>';
                $no++;
                $totCC += $b->cc_charge;
            }
        }

        foreach ($deposite as $b) {
            if ($b->dp_by == 'cc' || $b->dp_by == 'debit') {
                echo '<tr>';
                echo '<td  style="text-align:center">' . $no . '</td>';
                echo '<td>Down Payment</td>';
                echo '<td>' . $b->code . '</td>';
                echo '<td>' . $b->cc_number . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($b->amount) . '</td>';
                echo '<td>' . ucwords($b->Cashier->name) . '</td>';
                echo '</tr>';
                $no++;
                $totCC += $b->amount;
            }
        }
        ?>     
        <tr><td colspan="6">&nbsp;</td></tr>
        <tr>
            <td colspan="4" style="text-align: right">Total Credit Card Today :</td>
            <td style="text-align: right"><?php echo landa()->rp($totCC) ?></td>                               
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