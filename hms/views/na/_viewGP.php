<center><h3>GENERAL PAYMEN (CHECKOUT) TRANSACTION</h3></center>
<center>Date : <?php echo (!empty($date)) ? $date : '' ?></center>
<hr>

<table class="items table table-striped table-hover  table-condensed">
    <thead>
        <tr>
            <th rowspan="2" class="span1" style="text-align: center">NO</th>
            <th rowspan="2" class="span2" style="text-align: center">CODE</th>
            <th rowspan="2" class="span3" style="text-align: center">GUEST NAME</th>            
            <th colspan="2" class="span2" style="text-align: center">CASH</th>            
            <th rowspan="2" class="span2" style="text-align: center">DEBIT/CREDIT</th>            
            <th rowspan="2" class="span2" style="text-align: center">GUEST LEDGER</th>            
            <th rowspan="2" class="span2" style="text-align: center">CITY LEDGER</th>            
            <th rowspan="2" class="span2" style="text-align: center">CASHIER</th>            
        </tr>                        
        <tr>
            <th  class="span2" style="text-align: center">TUNAI</th>
            <th  class="span2" style="text-align: center">REFUND</th>                
        </tr>                        
    </thead>
    <tbody>
        <?php
        $bill = Bill::model()->findAll(array('condition' => 'is_cashier=1 and date_format(DATE(created),"%Y-%m-%d") = DATE("' . date('Y-m-d', strtotime($date)) . '")'));
        $totCash = 0;
        $totRefund = 0;
        $totCC = 0;
        $totCL = 0;
        $no = 1;
        foreach ($bill as $b) {
            $payCash = 0;
            $payRefund = 0;
            $payCC = 0;
            $payCA = 0;
            //chek dp masih ada atau tidak,,
            if ($b->by == 'cash') {
                if ($b->total < 0) {
                    $payRefund += $b->total;
                } else {
                    $payCash = $b->total;
                }
            } elseif ($b->by == 'cc' || $b->by == 'debit') {
                $payCC += $b->total;
            } elseif ($b->by == 'ca') {
                $payCA += $b->total;
            }
            $totCash += $payCash;
            $totRefund += $payRefund;
            $totCC += $payCC;
            $totCL += $payCA;

            echo '<tr>';
            echo '<td style="text-align:center">' . $no . '</td>';
            echo '<td style="text-align:center">' . $b->code . '</td>';
            echo '<td style="text-align:right">' . $b->Guest->guestName . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($payCash) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($payRefund) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($payCC) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp(0) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($payCA) . '</td>';
            echo '<td style="text-align:left">' . $b->Cashier->name . '</td>';
            echo '</tr>';
            $no++;
        }
        ?>
        <tr>
            <td colspan="3" style="text-align: right">Total General Payment:</td>
            <td style="text-align: right"><?php echo landa()->rp($totCash) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totRefund) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totCC) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp(0) ?></td>                             
            <td style="text-align: right"><?php echo landa()->rp($totCL) ?></td>                             
            <td style="text-align: right"></td>                             
        </tr>
    </tbody>
</table>