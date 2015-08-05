<center><h3>GUEST CHECKEDOUT</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>

<table class="items table table-striped table-hover  table-condensed">
    <thead>
        <tr>
            <th class="span1" style="text-align: center">NO</th>                       
            <th class="span2" style="text-align: center">DATE</th>                                   
            <th class="span2" style="text-align: center">CODE</th>                        
            <th class="span2" style="text-align: center">GUEST</th>                                    
            <th class="span2" style="text-align: center">ROOM</th>                                    
            <th class="span2" style="text-align: center">CASH</th>                                    
            <th class="span2" style="text-align: center">CREDIT</th>                                                
            <th class="span2" style="text-align: center">C/L</th>                                                
            <th class="span2" style="text-align: center">CASHIER</th>            
            <th class="span2" style="text-align: center">REMARKS</th>                                    
        </tr>                        
    </thead>
    <tbody>
        <?php
        $totCash = 0;
        $totCredit = 0;
        $totCL = 0;
        $no = 1;
        foreach ($bill as $b) {
            $cash = $b->cash;
            $credit = $b->cc_charge;
            $cl = $b->ca_charge;

            $totCash += $cash;
            $totCredit += $credit;
            $totCL += $cl;

            echo '<tr>';
            echo '<td  style="text-align:center">' . $no . '</td>';
            echo '<td>' . $b->created . '</td>';
            echo '<td>' . $b->code . '</td>';
            echo '<td>' . $b->Guest->guestName . '</td>';
            echo '<td>' . $b->roomNumber . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($cash, false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($credit, false) . '</td>';
            echo '<td style="text-align:right">' . landa()->rp($cl, false) . '</td>';
            echo '<td>' . ucwords($b->Cashier->name) . '</td>';
            echo '<td>' . $b->description . '</td>';
            echo '</tr>';
            $no++;
        }
        ?>     
        <tr><td colspan="10">&nbsp;</td></tr>
        <tr>
            <td colspan="5" style="text-align: right">Total  :</td>
            <td style="text-align: right"><?php echo landa()->rp($totCash, false) ?></td>                               
            <td style="text-align: right"><?php echo landa()->rp($totCredit, false) ?></td>                               
            <td style="text-align: right"><?php echo landa()->rp($totCL, false) ?></td>                               
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