<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_dpna')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_dpna" id="na_dpna">
    <center><h4>DOWNPAYMENT NOT APPLIED</h4></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">

    <table class="tbPrint" style="font-size: 10px;line-height: 8px !important">
        <thead>
            <tr>
                <th class="span1 judul print2" style="text-align: left">NO</th>                       
                <th class="span2 judul print2" style="text-align: left">DATE</th>                                   
                <th class="span2 judul print2" style="text-align: left">CODE</th>                        
                <th class="span2 judul print2" style="text-align: left">GUEST</th>                                    
                <th class="span2 judul print2" style="text-align: right">CASH</th>                                    
                <th class="span2 judul print2" style="text-align: right">CREDIT</th>                                                
                <th class="span2 judul print2" style="text-align: left">CASHIER</th>            
                <th class="span2 judul print2" style="text-align: left">REMARKS</th>                                    
            </tr>                        
        </thead>
        <tbody>
            <?php
            $totDpCash = 0;
            $totDpCredit = 0;
            $no = 1;
            foreach ($naDpUnused as $b) {
                $cash = 0;
                $credit = 0;
                if ($b->Deposite->dp_by == 'cash') {
                    $totDpCash += $b->Deposite->amount;
                    $cash = $b->Deposite->amount;
                } else {
                    $totDpCredit += $b->Deposite->amount;
                    $credit = $b->Deposite->amount;
                }

                echo '<tr>';
                echo '<td class="print2 isi"  style="text-align:left;border-bottom:none !important;border-top:none !important">' . $no . '</td>';
                echo '<td class="print2 isi"  style="text-align:left;border-bottom:none !important;border-top:none !important">' . $b->Deposite->created . '</td>';
                echo '<td class="print2 isi"  style="text-align:left;border-bottom:none !important;border-top:none !important">' . $b->Deposite->code . '</td>';
                echo '<td class="print2 isi"  style="text-align:left;border-bottom:none !important;border-top:none !important">' . $b->Deposite->Guest->guestName . '</td>';
                echo '<td class="print2 isi"  style="text-align:right;border-bottom:none !important;border-top:none !important">' . landa()->rp($cash,false) . '</td>';
                echo '<td class="print2 isi"  style="text-align:right;border-bottom:none !important;border-top:none !important">' . landa()->rp($credit,false) . '</td>';
                echo '<td class="print2 isi"  style="text-align:left;border-bottom:none !important;border-top:none !important">' . ucwords($b->Deposite->Cashier->name) . '</td>';
                echo '<td class="print2 isi"  style="text-align:left;border-bottom:none !important;border-top:none !important">' . $b->Deposite->description . '</td>';
                echo '</tr>';
                $no++;
            }
            ?>    
            <tr>
                <td class="print2" colspan="4" style="text-align: right">Total Downpayment Not Applied:</td>
                <td class="print2" style="text-align: right"><?php echo landa()->rp($totDpCash,false) ?></td>                               
                <td class="print2" style="text-align: right"><?php echo landa()->rp($totDpCredit,false) ?></td>                               
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
        .na_dpna{visibility: visible;} 
        .na_dpna{width: 100%;top: 0px;left: 0px;position: absolute;} 

    }
</style>