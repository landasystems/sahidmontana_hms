<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_ck')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_ck" id="na_ck">
    <center><h4>GUEST CHECKEDOUT</h4></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">

    <table class=" tbPrint" style="font-size: 10px;line-height: 10px !important;">
        <thead>
            <tr>
                <th class="span1 judul print2"  style="text-align: left">NO</th>                       
                <th class="span2 judul print2" width="130" style="text-align: left">DATE</th>                                   
                <th class="span2 judul print2" width="100" style="text-align: left">CODE</th>                        
                <th class="span2 judul print2" style="text-align: left">GUEST</th>                                    
                <th class="span2 judul print2" style="text-align: left">ROOM</th>                                    
                <th class="span2 judul print2" style="text-align: right">CASH</th>                                    
                <th class="span2 judul print2" style="text-align: right">CREDIT</th>                                                
                <th class="span2 judul print2" style="text-align: right">C/L</th>                                                
                <th class="span2 judul print2" style="text-align: left">CASHIER</th>            
                <th class="span2 judul print2" style="text-align: left">REMARKS</th>                                    
            </tr>                        
        </thead>
        <tbody>
            <?php
            $totCash = 0;
            $totCredit = 0;
            $totCL = 0;
            $no = 1;
            $deposite = array_filter($naDet, function($naDet) {
                        return $naDet['bill_id'] != 0;
                    });
            foreach ($deposite as $b) {
                $cash = $b->Bill->cash;
                $credit = $b->Bill->cc_charge;
                $cl = $b->Bill->ca_charge;

                $totCash += $cash;
                $totCredit += $credit;
                $totCL += $cl;

                echo '<tr>';
                echo '<td class="print2 isi"  style="text-align:left;border-bottom:none !important;border-top:none !important">' . $no . '</td>';
                echo '<td class="print2 isi" style="border-bottom:none !important;border-top:none !important">' . $b->Bill->created . '</td>';
                echo '<td class="print2 isi" style="border-bottom:none !important;border-top:none !important">' . $b->Bill->code . '</td>';
                echo '<td class="print2 isi" style="border-bottom:none !important;border-top:none !important">' . $b->Bill->Guest->guestName . '</td>';
                echo '<td class="print2 isi" style="border-bottom:none !important;border-top:none !important">' . $b->Bill->roomNumber . '</td>';
                echo '<td class="print2 isi" style="text-align:right;border-bottom:none !important;border-top:none !important"">' . landa()->rp($cash, false) . '</td>';
                echo '<td class="print2 isi" style="text-align:right;border-bottom:none !important;border-top:none !important"">' . landa()->rp($credit, false) . '</td>';
                echo '<td class="print2 isi" style="text-align:right;border-bottom:none !important;border-top:none !important"">' . landa()->rp($cl, false) . '</td>';
                echo '<td class="print2 isi" style="border-bottom:none !important;border-top:none !important">' . ucwords($b->Bill->Cashier->name) . '</td>';
                echo '<td class="print2 isi" style="border-bottom:none !important;border-top:none !important">' . $b->Bill->description . '</td>';
                echo '</tr>';
                $no++;
            }
            ?>
            <tr>
                <td class="print2" colspan="5" style="text-align: right">Total  :</td>
                <td class="print2" style="text-align: right"><?php echo landa()->rp($totCash, false) ?></td>                               
                <td class="print2" style="text-align: right"><?php echo landa()->rp($totCredit, false) ?></td>                               
                <td class="print2" style="text-align: right"><?php echo landa()->rp($totCL, false) ?></td>                               
                <td class="print2" style="text-align: right"></td>                             
                <td class="print2" style="text-align: right"></td>                             
            </tr>
        </tbody>
    </table>
    <br>
    <table>
        <tr>
            <td style="padding: 0px;width: 30%;font-size: 10px" class="span2">Audit By</td>        
            <td style="padding: 0px;font-size: 10px">: <?php echo $model->Cashier->name; ?></td>
        </tr>    
        <tr>
            <td style="padding: 0px;font-size: 10px">Printed Time</td>        
            <td style="padding: 0px;font-size: 10px">: <?php echo date('l d-M-Y H:i:s', strtotime($model->created)); ?></td>
        </tr>    
    </table>
</div>


<style type="text/css">
    @media print
    {
        body {visibility:hidden;}
        .na_ck{visibility: visible;} 
        .na_ck{width: 100%;top: 0px;left: 0px;position: absolute;} 

    }
</style>