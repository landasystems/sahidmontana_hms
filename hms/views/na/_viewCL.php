<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_cl')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_cl" id="na_cl">
    <center><h3>DETAIL CITY LEDGER</h3></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">

    <table class="items table table-striped table-hover table-condensed">
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

            $filterBill = array_filter($naDet, function($naDet) {
                return $naDet['bill_id'] != 0;
            });
            foreach ($filterBill as $b) {
                if ($b->Bill->ca_charge != 0 and !empty($b->Bill->ca_user_id)) {
                    echo '<tr>';
                    echo '<td style="text-align:center">' . $no . '</td>';
                    echo '<td>General Payment</td>';
                    echo '<td>' . $b->Bill->code . '</td>';
                    echo '<td>' . $user[$b->Bill->ca_user_id]['name'] . '</td>';
                    echo '<td>' . landa()->rp($b->Bill->ca_charge) . '</td>';
                    echo '<td>' . ucwords($b->Bill->Cashier->name) . '</td>';
                    echo '</tr>';
                    $no++;
                    $totCL += $b->Bill->ca_charge;
                }
            }

            $filterBillCharge = array_filter($naDet, function($naDet) {
                return $naDet['bill_charge_id'] != 0;
            });
            foreach ($filterBillCharge as $b) {
                if ($b->BillCharge->ca_charge != 0 and !empty($b->BillCharge->ca_user_id)) {
                    $billChargeId = $b->BillCharge->id;
                    $filterBillChargeDet = array_filter($billChargeDet, function($billChargeDet) use($billChargeId) {
                        return $billChargeDet['bill_charge_id'] == $billChargeId;
                    });

                    foreach ($filterBillChargeDet as $key => $value) {
                        $name = getRootName($value['charge_additional_id'], $additional, $category, $category_all);
                    }

                    echo '<tr>';
                    echo '<td  style="text-align:center">' . $no . '</td>';
                    echo '<td>' . $name . '</td>';
                    echo '<td>' . $b->BillCharge->code . '</td>';
                    echo '<td>' . $user[$b->BillCharge->ca_user_id]['name'] . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($b->BillCharge->ca_charge) . '</td>';
                    echo '<td>' . ucwords($b->BillCharge->Cashier->name) . '</td>';
                    echo '</tr>';
                    $no++;
                    $totCL += $b->BillCharge->ca_charge;
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
    <br>
    <table>
        <tr>
            <td style="padding: 0px;width: 30%" class="span2">Audit By</td>        
            <td style="padding: 0px">: <?php echo $model->Cashier->name; ?></td>
        </tr>    
        <tr>
            <td style="padding: 0px">Printed Time</td>        
            <td style="padding: 0px">: <?php echo date('l d-M-Y H:i:s', strtotime($model->created)); ?></td>
        </tr>    
    </table>
</div>


<style type="text/css">
    @media print
    {
        body {visibility:hidden;}
        .na_cl{visibility: visible;} 
        .na_cl{width: 100%;top: 0px;left: 0px;position: absolute;} 

    }
</style>
