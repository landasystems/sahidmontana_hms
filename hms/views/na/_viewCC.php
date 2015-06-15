<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_cc')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_cc" id="na_cc">
    <center><h3>DETAIL CREDIT CARD</h3></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">

    <table class="items table table-striped table-hover table-condensed">
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
            $filterBill = array_filter($naDet, function($naDet) {
                return $naDet['bill_id'] != 0;
            });
            foreach ($filterBill as $b) {
                if ($b->Bill->cc_charge != 0) {
                    echo '<tr>';
                    echo '<td style="text-align:center">' . $no . '</td>';
                    echo '<td>General Payment</td>';
                    echo '<td>' . $b->Bill->code . '</td>';
                    echo '<td>' . $b->Bill->cc_number . '</td>';
                    echo '<td>' . landa()->rp($b->Bill->cc_charge) . '</td>';
                    echo '<td>' . ucwords($b->Bill->Cashier->name) . '</td>';
                    echo '</tr>';
                    $no++;
                    $totCC += $b->Bill->cc_charge;
                }
            }

            $filterBillCharge = array_filter($naDet, function($naDet) {
                return $naDet['bill_charge_id'] != 0;
            });
            foreach ($filterBillCharge as $b) {
                if ($b->BillCharge->cc_charge != 0) {
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
                    echo '<td>' . $b->BillCharge->cc_number . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($b->BillCharge->ca_charge) . '</td>';
                    echo '<td>' . ucwords($b->BillCharge->Cashier->name) . '</td>';
                    echo '</tr>';
                    $no++;
                    $totCC += $b->BillCharge->cc_charge;
                }
            }

            $filterDeposite = array_filter($naDet, function($naDet) {
                return $naDet['deposite_id'] != 0;
            });
            foreach ($filterDeposite as $b) {
                if ($b->Deposite->dp_by == 'cc' || $b->Deposite->dp_by == 'debit') {
                    echo '<tr>';
                    echo '<td  style="text-align:center">' . $no . '</td>';
                    echo '<td>Down Payment</td>';
                    echo '<td>' . $b->Deposite->code . '</td>';
                    echo '<td>' . $b->Deposite->cc_number . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($b->Deposite->amount) . '</td>';
                    echo '<td>' . ucwords($b->Deposite->Cashier->name) . '</td>';
                    echo '</tr>';
                    $no++;
                    $totCC += $b->Deposite->amount;
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
        .na_cc{visibility: visible;} 
        .na_cc{width: 100%;top: 0px;left: 0px;position: absolute;} 

    }
</style>
