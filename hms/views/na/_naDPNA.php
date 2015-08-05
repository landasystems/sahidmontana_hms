<center><h3>DEPOSITE NOT APPLIED</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>

<table class="items table table-striped table-hover  table-condensed">
    <thead>
        <tr>
            <th class="span1" style="text-align: center">NO</th>                       
            <th class="span2" style="text-align: center">DATE</th>                                   
            <th class="span2" style="text-align: center">CODE</th>                        
            <th class="span2" style="text-align: center">GUEST</th>                                    
            <th class="span2" style="text-align: center">CASH</th>                                    
            <th class="span2" style="text-align: center">CREDIT</th>                                                
            <th class="span2" style="text-align: center">CASHIER</th>            
            <th class="span2" style="text-align: center">REMARKS</th>                                    
        </tr>                        
    </thead>
    <tbody>
        <?php
        $totDpCash = 0;
        $totDpCredit = 0;
        $no = 1;
        foreach ($deposite_unapplied as $b) {
            $cash = 0;
            $credit = 0;
            if ($b->dp_by == 'cash') {
                $totDpCash += $b->amount;
                $cash = $b->amount;
            } else {
                $totDpCredit += $b->amount;
                $credit = $b->amount;
            }

            echo '<tr>';
            echo '<td  style="text-align:center">' . $no . '</td>';
            echo '<td>' . $b->created . '</td>';
            echo '<td>' . $b->code . '</td>';
            echo '<td>' . $b->Guest->guestName . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($cash, false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($credit, false) . '</td>';
            echo '<td>' . ucwords($b->Cashier->name) . '</td>';
            echo '<td>' . $b->description . '</td>';
            echo '</tr>';
            $no++;
        }
        ?>     
        <tr><td colspan="8">&nbsp;</td></tr>
        <tr>
            <td colspan="4" style="text-align: right">Total Deposite Not Applied:</td>
            <td style="text-align: right"><?php echo landa()->rp($totDpCash, false) ?></td>                               
            <td style="text-align: right"><?php echo landa()->rp($totDpCredit, false) ?></td>                               
            <td style="text-align: right"></td>                             
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